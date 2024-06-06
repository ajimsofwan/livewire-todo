<div>
  @if (session('error'))
    <div class="p-4 mb-4 mt-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
      <span class="font-medium">Error!</span> {{ session('error') }}
    </div>
  @endif
  @include('livewire.includes.create-todo-box')
  @include('livewire.includes.search-box')

  <div id="todos-list">

    @foreach ($todos as $todo)
      @include('livewire.includes.todo-card')
    @endforeach
    <div class="my-2">
      {{ $todos->links() }}
    </div>
  </div>
</div>
