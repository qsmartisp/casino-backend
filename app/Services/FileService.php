<?php

namespace App\Services;

use App\Enums\File\Type;
use App\Models\File;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Class FileService
 *
 * @package App\Services
 */
class FileService
{
    /**
     * Store requested files
     *
     * @param array $files
     * @param Type $type
     *
     * @return Collection
     */
    public function store(array $files, Type $type): Collection
    {
        $fileIds = [];

        foreach ($files as $file) {
            $savedFile = $this->save($file);

            $createdFile = $this->query()->create([
                'type' => $type->value,
                'name' => $savedFile['name'],
                'original_name' => $savedFile['original_name'],
                'extension' => $savedFile['extension'],
                'mime_type' => $savedFile['mime_type'],
                'size' => $savedFile['size'],
                'path' => $savedFile['path'],
            ]);

            $fileIds[] = $createdFile->id;
        }

        return $this->query()
            ->whereIn('id', $fileIds)
            ->get();
    }

    /**
     * Save uploaded file
     *
     * @param UploadedFile $file
     *
     * @return array
     */
    public function save(UploadedFile $file): array
    {
        $hashedName = md5($file->getClientOriginalName() . now());
        $extension = mb_strtolower($file->getClientOriginalExtension());

        /** @var string $path */
        $path = Storage::disk('public')
            ->putFileAs('uploads', $file, $hashedName . '.' . $extension);

        return [
            'name' => $hashedName,
            'original_name' => $file->getClientOriginalName(),
            'extension' => $extension,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'path' => $path,
        ];
    }

    public static function makeUploadedFileFromUrl(string $url): UploadedFile
    {
        $info = pathinfo($url);
        $contents = file_get_contents($url); // todo: 404 handle

        // todo: config
        $file = '/tmp/' . $info['basename'];
        file_put_contents($file, $contents);
        chmod($file, 0664);

        return new UploadedFile($file, $info['basename']);
    }

    public function deleteByGamePaths(SupportCollection $gamePaths): bool
    {
        // todo
        return Storage::disk('public')->delete($gamePaths->toArray());
    }

    /**
     * @throws RuntimeException
     */
    public function deleteOldGamesImages(SupportCollection $games): void
    {
        $gamePaths = collect();

        /** @var Game $game */
        foreach ($games as $game) {
            $gamePaths->push($game->images()->pluck('path')->first());

            $game->images()->detach();
        }

        if (false === $this->deleteByGamePaths($gamePaths)) {
            throw new RuntimeException("Can't delete game images from storage");
        }
    }

    public function query(): Builder|File
    {
        return File::query();
    }

    public function destroyFileByPath(string $path): bool
    {
        return FileFacade::delete($path);
    }
}
