<div>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/admin/students' => 'Students',
        '' => 'List',
    ]" />
        <div class="flex justify-between mt-1">
            <div class="font-bold text-3xl">Students</div>
            <div> {{ $data }} </div>
        </div>
        <div>
            <form wire:submit="save" class="w-full max-w-sm flex mt-2">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="fileinput">
                        Pilih Berkas
                    </label>
                    <input class="shadow appearance-none border rounded 2-full py-2 px-3 text-gray-700 leading-tight focus:outline-none foucs:shadow-outline" id="fileinput" type="file" wire:model="file">
                </div>
                <div class="flex items-center jutify-between mt-3">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded foucs:outline-none fouces:shadow-outlines" type="submit">
                        Unggah
                    </button>
                </div>
            </form>
        </div>
</div>

