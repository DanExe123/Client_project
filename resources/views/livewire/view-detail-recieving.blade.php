<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6">
    {{-- Header --}}
    <div>
        <div class="flex justify-start">
            <h2 class="text-lg font-bold text-gray-800">View</h2>
        </div>
    </div>

    {{-- Breadcrumb --}}
    <div class="text-gray-500 flex text-start gap-3">
        <a href="{{ route('recieving') }}"><span class="text-gray-500 font-medium">Recieving</span></a>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium">View detail recieving</span>
    </div>
    <hr>
   
        <hr>
        <div class=" flex justify-center gap-6">
            <a href="{{ route('recieving') }}">
                <x-button label="Cancel" primary flat class="!text-sm mt-2" />
            </a>
            <x-button spinner type="submit" primary label="Submit" class="flex justify-center !w-24" />
        </div>
    </form>
</div>