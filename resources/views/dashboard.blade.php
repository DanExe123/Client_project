<x-layouts.app :title="__('Dashboard')">
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
    class="flex justify-center items-center h-screen"
>
    <div x-cloak class="flex items-center gap-10 p-8 bg-white rounded-xl border border-gray-200">
        <!-- Left: Text content -->
        <div class="text-left space-y-2">
            <h2 class="text-xl font-semibold text-gray-700" x-text="formattedDate"></h2>
            <p class="text-3xl font-bold text-gray-900" x-text="formattedTime"></p>

            @role('admin')
                <h1 class="text-3xl font-bold mt-2">Hello</h1>
                <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span> 
            @endrole 

            @role('user')
                <h1 class="text-3xl font-bold mt-2">Hello Employee</h1>
                <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span> 
            @endrole 
        </div>

        <!-- Right: Image -->
        <img src="{{ asset('logo/logo.jpg') }}" alt="Dnexus" class="max-w-[400px] h-80 object-cover border border-white" />
    </div>
</div>

  @include('partials.toast')

</x-layouts.app>
