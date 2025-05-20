<x-layouts.app :title="__('Dashboard')">
 <!-- Centered Greeting Section -->

  <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

      <!-- Real-Time Date and Time -->
      <div 
          x-data="{
              date: new Date(),
              init() {
                  setInterval(() => this.date = new Date(), 1000);
              },
              get formattedDate() {
                  return this.date.toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
              },
              get formattedTime() {
                  return this.date.toLocaleTimeString();
              }
          }"
          x-init="init"
          class="flex flex-col items-center justify-center h-48 text-center text-gray-800 bg-white rounded-lg shadow-lg"
      >
          <h2 class="text-xl font-semibold" x-text="formattedDate"></h2>
          <p class="text-3xl font-bold mt-2" x-text="formattedTime"></p>

          
          @role('admin')
          <h1 class="text-bold text-3xl">Hello</h1>
          <span class="truncate font-semibold">{{ auth()->user()->name }}</span> 
          @endrole 

          @role('user')
              <h1 class="text-bold text-3xl">Hello Employee</h1>
              <span class="truncate font-semibold">{{ auth()->user()->name }}</span> 
          @endrole 
      </div>


  @include('partials.toast')

</x-layouts.app>
