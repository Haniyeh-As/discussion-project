<?php


namespace App\Repositories;


use App\channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelRepository
{

    /**
     * All Channel List
     */
    public function all()
    {
        return channel::all();
    }
    /**
     * Create New Channel
     * @param $name
     */
    public function create($name): void
    {
        channel::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    /**
     * Update Channel
     * @param $id
     * @param $name
     */
    public function update($id, $name)
    {
        channel::find($id)->update([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }

    /**
     * Delete Channel
     * @param $id
     */
    public function delete($id)
    {
        channel::destroy($id);
    }
}
