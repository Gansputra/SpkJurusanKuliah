<table class="min-w-full text-sm">
    <thead>
        <tr class="bg-gray-50">
            <th class="px-4 py-2 text-left text-gray-500 font-semibold">Jurusan</th>
            @foreach($criteria as $c)
            <th class="px-4 py-2 text-center text-gray-700 font-bold">{{ $c['kode'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($alternatives as $i => $alt)
        <tr class="border-t border-gray-50 {{ $i % 2 ? 'bg-gray-50/50' : '' }}">
            <td class="px-4 py-2 font-semibold text-gray-700">{{ $alt['nama'] }}</td>
            @foreach($criteria as $j => $c)
            <td class="px-4 py-2 text-center font-mono text-gray-600">
                {{ number_format($matrix[$i][$j], $format) }}
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
