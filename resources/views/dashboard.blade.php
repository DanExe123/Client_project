<x-layouts.app :title="__('Dashboard')">

    
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3"> 
        </div>
        @role('admin')
      <h1 class="text-bold text-3xl"> hello </h1><span class="truncate font-semibold">{{ auth()->user()->name }}</span> 
      @endrole 
      @role('user')
      <h1 class="text-bold text-3xl"> hello Employee</h1><span class="truncate font-semibold">{{ auth()->user()->name }}</span> 
      @endrole 
    </div>
</x-layouts.app>
