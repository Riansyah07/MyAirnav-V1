<?php

namespace App\Services;

use App\Models\Document;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class VsmService
{
    protected $stemmer;
    protected $stopWordRemover;

    public function __construct()
    {
        $stemmerFactory = new StemmerFactory();
        $this->stemmer = $stemmerFactory->createStemmer();
        $stopWordRemoverFactory = new StopWordRemoverFactory();
        $this->stopWordRemover = $stopWordRemoverFactory->createStopWordRemover();
    }

    public function getRankedDocuments(string $query): array
    {
        $queryTerms = $this->preprocess($query);
        $queryVector = $this->calculateTfIdfForQuery($queryTerms);
        if (empty($queryVector)) {
            return [];
        }

        // --- OPTIMASI PENCARIAN ---
        $candidateDocuments = Document::whereNotNull('vector')
            ->where(function ($dbQuery) use ($queryTerms) {
                foreach ($queryTerms as $term) {
                    $dbQuery->orWhere('title', 'LIKE', '%' . $term . '%')
                            ->orWhere('note', 'LIKE', '%' . $term . '%');
                }
            })
            ->get();
        
        $rankedResults = [];

        foreach ($candidateDocuments as $doc) {
            if (empty($doc->vector)) {
                continue;
            }
            $docVector = json_decode($doc->vector, true);
            $similarityScore = $this->calculateCosineSimilarity($queryVector, $docVector);
            
            if ($similarityScore > 0.01) {
                $rankedResults[] = [
                    'id' => $doc->id,
                    'score' => $similarityScore
                ];
            }
        }

        usort($rankedResults, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_column($rankedResults, 'id');
    }


    /**
     * ===================================================================
     * FUNGSI-FUNGSI PERHITUNGAN MATEMATIS (INTI VSM)
     * ===================================================================
     */

    public function calculateCosineSimilarity(array $queryVector, array $docVector): float
    {
        $dotProduct = 0;
        $queryMagnitude = 0;
        $docMagnitude = 0;
        $allTerms = array_unique(array_merge(array_keys($queryVector), array_keys($docVector)));

        foreach ($allTerms as $term) {
            $queryWeight = $queryVector[$term] ?? 0;
            $docWeight = $docVector[$term] ?? 0;
            $dotProduct += $queryWeight * $docWeight;
            $queryMagnitude += $queryWeight ** 2;
            $docMagnitude += $docWeight ** 2;
        }

        $magnitude = sqrt($queryMagnitude) * sqrt($docMagnitude);
        return $magnitude == 0 ? 0 : $dotProduct / $magnitude;
    }

    public function calculateTfIdfForQuery(array $queryTerms): array
    {
        $idfMap = $this->getInverseDocumentFrequencyMap();
        $vector = [];
        $termCounts = array_count_values($queryTerms);
        $totalTermsInQuery = count($queryTerms);

        if ($totalTermsInQuery === 0) return [];

        foreach ($termCounts as $term => $count) {
            $tf = $count / $totalTermsInQuery;
            $idf = $idfMap[$term] ?? 0;
            $vector[$term] = $tf * $idf;
        }
        
        return $vector;
    }

    protected function calculateTfIdfForDocument(array $docTerms): array
    {
        $idfMap = $this->getInverseDocumentFrequencyMap();
        $vector = [];
        $termCounts = array_count_values($docTerms);
        $totalTermsInDoc = count($docTerms);

        if ($totalTermsInDoc === 0) return [];

        foreach ($termCounts as $term => $count) {
            $tf = $count / $totalTermsInDoc;
            $idf = $idfMap[$term] ?? 0;
            $vector[$term] = $tf * $idf;
        }
        
        return $vector;
    }

    protected function getInverseDocumentFrequencyMap(): array
    {
        return Cache::remember('idf_map', 60 * 24, function () { // Cache diperpanjang jadi 24 jam
            $documentCount = Document::count();
            if ($documentCount === 0) {
                return [];
            }
            
            $docFrequency = [];
            // DIPERBAIKI: Ambil hanya kolom yang dibutuhkan agar lebih efisien
            $allDocuments = Document::select('title', 'note')->get();

            foreach ($allDocuments as $document) {
                $textContent = $document->title . ' ' . $document->note;
                if(empty(trim($textContent))) continue;

                $terms = array_unique($this->preprocess($textContent));
                foreach ($terms as $term) {
                    $docFrequency[$term] = ($docFrequency[$term] ?? 0) + 1;
                }
            }

            $idfMap = [];
            foreach ($docFrequency as $term => $count) {
                // Gunakan log10 untuk hasil yang lebih stabil
                $idfMap[$term] = log10($documentCount / $count);
            }
            
            return $idfMap;
        });
    }


    /**
     * ===================================================================
     * FUNGSI-FUNGSI HELPER (PEMBANTU)
     * ===================================================================
     */

    public function generateAndSaveVector(Document $document)
    {
        // DIPERBAIKI: Sumber teks sekarang hanya dari database
        $textContent = $document->title . ' ' . $document->note;

        if (empty(trim($textContent))) {
            $document->vector = null;
        } else {
            $terms = $this->preprocess($textContent);
            $vector = $this->calculateTfIdfForDocument($terms);
            $document->vector = json_encode($vector);
        }
        
        $document->save();
        Cache::forget('idf_map');
    }

    public function preprocess(string $text): array
    {
        $text = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', '', $text));
        $tokens = explode(' ', $text);
        $filtered = $this->stopWordRemover->remove(implode(' ', $tokens));
        $tokens = array_filter(explode(' ', $filtered));

        $stemmedTokens = [];
        foreach ($tokens as $token) {
            $stemmedTokens[] = $this->stemmer->stem($token);
        }
        return array_filter($stemmedTokens);
    }
}