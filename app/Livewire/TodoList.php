<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class TodoList extends Component
{
  use WithPagination;

  #[Rule('required|min:3|max:50')]
  public $name;

  public $search;
  public $updateId;
  #[Rule('required|min:3|max:50')]
  public $updateName;


  public function messages()
  {
    return [
      'required' => 'Harus diisi.',
      'min' => 'Minimal :min karakter',
      'max' => 'Maksimal :max karakter',
    ];
  }

  public function create()
  {
    $validated = $this->validateOnly('name');
    Todo::create($validated);
    $this->reset('name');
    session()->flash('success', 'Tersimpan.');
    $this->resetPage();
  }

  public function edit(Todo $todo)
  {
    $this->updateId = $todo->id;
    $this->updateName = $todo->name;
  }

  public function update()
  {
    $this->validateOnly('updateName');
    $todo = Todo::find($this->updateId);
    if ($todo) {
      $todo->name = $this->updateName;
      $todo->save();
    }
    $this->cancel();
  }

  public function cancel()
  {
    $this->reset('updateId', 'updateName');
  }

  public function toggle(Todo $todo)
  {
    $todo->completed = !$todo->completed;
    $todo->save();
  }

  public function destroy($id)
  {
    try {
      ToDo::findOrFail($id)->delete();
    } catch (\Exception $e) {
      session()->flash('error', 'Gagal menghapus.');
      return;
    }
  }

  public function render()
  {
    if ($this->search)  $this->resetPage();
    $todos = Todo::orderBy('completed', 'asc')->latest()->where('name', 'like', "%{$this->search}%")->paginate(5);
    return view('livewire.todo-list', [
      'todos' => $todos
    ]);
  }
}
