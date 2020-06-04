<?php

namespace App\Repositories;

use App\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileRepository
{
	/**
	 * Get all files related to *Post* / *Event* / *Tool*.
	 *
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 *
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getAll($parent)
	{
		return $parent->files()->paginate(10);
	}

	/**
	 * Create a file related to the given parent.
	 *
	 * @param mixed $parent The *Post* / *Event* / *Tool* object.
	 * @param array $data The file data.
	 *
	 * @return \App\File
	 */
	public function create($parent, array $data)
	{
		$path = Str::plural(Str::lower(Str::after(get_class($parent), 'App\\')));
		$path = Storage::disk('local')->put("files/$path/" . $parent->id, $data['file']);

		return $parent->files()->create([
			'name' => $data['file']->getClientOriginalName(),
			'path' => $path,
			'mime' => Storage::mimeType($path)
		]);
	}

	/**
	 * Update an existing file.
	 *
	 * @param \App\File $file The file object.
	 * @param array $data The file data.
	 *
	 * @return void
	 */
	public function update(File $file, array $data)
	{
		Storage::disk('local')->delete($file->path);

		$parent = $file->resourceable;
		$path = Str::plural(Str::lower(Str::after(get_class($parent), 'App\\')));
		$path = Storage::disk('local')->put("files/$path/" . $parent->id, $data['file']);

		$file->update([
			'name' => $data['file']->getClientOriginalName(),
			'path' => $path,
			'mime' => Storage::mimeType($path)
		]);
	}

	/**
	 * Delete an existing file.
	 *
	 * @param \App\File $file The file object.
	 *
	 * @return void
	 */
	public function delete(File $file)
	{
		$file->delete();
	}
}