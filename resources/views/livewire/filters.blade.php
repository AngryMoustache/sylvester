<div class="flex">
    <div class="w-1/5">
        <ul>
            @foreach($data as $key => $item)
                <li wire:click="loadItem({{ $key }})">{{ $item->name }}</li>
            @endforeach
        </ul>
    </div>

    <div class="w-4/5">
        <h1>{{ $data[$current]->name }}</h1>
        @dump($data[$current]->data)
    </div>

    <script>
        console.log({!! Js::from($item->data) !!})
    </script>
</div>
