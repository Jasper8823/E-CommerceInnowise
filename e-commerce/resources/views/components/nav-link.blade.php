@props(['active' => false, 'href' => '/'])
<a href = {{$href}} class="{{$active ? 'text-gray-300 bg-gray-900 text-gray' : 'text-gray-300 hover:bg-gray-700 hover:text-white'}}block rounded-md px-3 py-2 text-base font-medium" aria-current = "{{$active ? 'page' : 'false'}}">
    {{$slot}}
</a>
