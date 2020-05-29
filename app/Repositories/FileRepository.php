<?php

namespace App\Repositories;

use App\File;
use Illuminate\Support\Facades\Storage;

class FileRepository
{
	/**
	 * Get all files related to *Post* / *Event*.
	 *
	 * @param mixed $resource The *Post* / *Event* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getFilesFor($resource)
	{
		return $resource->files()->paginate(10);
	}

	/**
	 * Create a file related to the given resource.
	 *
	 * @param mixed $resource The *Post* / *Event* object.
	 * @param string $path The folder path to save to.
	 * @param array $data The file data.
	 *
	 * @return \App\File
	 */
	public function create($resource, string $path, array $data)
	{
		$path = Storage::disk('local')->put("files/${path}/" . $resource->id, $data['file']);
		$mime = Storage::mimeType($path);

		return $resource->files()->create([
			'path' => $path,
			'mime' => $mime
		]);
	}

	/**
	 * Update an existing file.
	 *
	 * @param File $file The file object.
	 * @param string $path The folder path to save to **with the resource id**.
	 * @param array $data The file data.
	 *
	 * @return void
	 */
	public function update(File $file, string $path, array $data)
	{
		Storage::disk('local')->delete($file->path);
		$path = Storage::disk('local')->put("files/${path}", $data['file']);
		$mime = Storage::mimeType($path);

		$file->update([
			'path' => $path,
			'mine' => $mime
		]);
	}

	/**
	 * Delete an existing file.
	 *
	 * @param File $file The file object.
	 *
	 * @return void
	 */
	public function delete(File $file)
	{
		$file->delete();
	}
}