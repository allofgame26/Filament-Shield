<div>
    @if (session()->has('message'))
        <div class='text-2xl'>
            {{ session('message') }}
        </div>
        <button type="button" wire:click="delSession">Now Register</button>
    @endif
    <form action="post" wire:submit="save">
        {{ $this->form }}
        <button type="submit" class="my-4 bg-green-500 w-40 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Save
        </button>
    </form>
</div>
