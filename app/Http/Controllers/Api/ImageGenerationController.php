<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePromptRequest;
use App\Http\Resources\ImageGenerationResource;
use App\Services\OpenAiService;
use Illuminate\Http\Request;

class ImageGenerationController extends Controller
{
    public function __construct(private OpenAiService $openAIService) {

    }
    public function index(Request $request){
        $user = $request->user();
        $imageGenerations = $user->imageGenerations()->latest()->paginate(10);
        return response()->json([
            'message' => 'Image generations fetched successfully',
            'data' => ImageGenerationResource::collection($imageGenerations),
        ]);
    }
    public function store(GeneratePromptRequest $request){
        $user = $request->user();
        $image = $request->file('image');
        
        $originalImage = $image->getClientOriginalName();
        $sanitizedName = preg_replace('/[^A-Za-z0-9\.]/', '_', pathinfo($originalImage, PATHINFO_FILENAME));
        $extension = $image->getClientOriginalExtension();
        $fileName = $sanitizedName . '_' . time() . '.' . $extension;
        $imagePath = $image->storeAs('uploads/original', $fileName, 'public');

        $generatedPrompt = $this->openAIService->generatePromptFromImage($image);
        $imageGeneration = $user->imageGenerations()->create([
            'image_path' => $imagePath,
            'generated_prompt' => $generatedPrompt,
            'original_filename' => $originalImage,
            'file_size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),
        ]);
        return response()->json([
            'message' => 'Image generated successfully',
            'data' => new ImageGenerationResource($imageGeneration),
        ]);
        
    }
}
