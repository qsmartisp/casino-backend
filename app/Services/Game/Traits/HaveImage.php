<?php

namespace App\Services\Game\Traits;

use App\Enums\File\Type;
use App\Models\File;
use App\Models\Game;
use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use RuntimeException;

trait HaveImage
{
    protected static FileService $fileRepository;

    /**
     * @throws RuntimeException
     */
    protected static function makeImage(UploadedFile $file): File
    {
        $image = static::$fileRepository->store([$file], Type::Game)->first();

        self::clearTmpFile($file);

        return $image;
    }

    protected static function makeFile(string $url): UploadedFile
    {
        return FileService::makeUploadedFileFromUrl($url);
    }

    protected static function clearTmpFile(UploadedFile $file): void
    {
        if (static::$fileRepository->destroyFileByPath($file->getPathname()) === false) {
            throw new RuntimeException("Can't delete file from /tmp");
        }
    }

    private function setImage(
        Game $game,
        string $imageName,
        ?File $oldImage = null,
    ): ?bool {
        try {
            $file = self::makeFile($imageName);

            if (
                $oldImage
                && $file->getSize() === $oldImage->size
                && $file->getClientOriginalName() === $oldImage->original_name
            ) {
                self::clearTmpFile($file);

                return null;
            }

            $image = self::makeImage($file);
            $game->images()->syncWithPivotValues([$image->id], ['identity_type' => Game::class]);

            return true;
        } catch (\ErrorException $exception) {
            return false;
        }
    }
}
