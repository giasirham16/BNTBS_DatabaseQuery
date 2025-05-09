<style>
    /* Kontainer relatif untuk input */
    .search-container {
        position: relative;
        width: 40vw;
    }

    /* Style untuk input search agar padding kanan cukup untuk ikon clear */
    .search-container input.form-control {
        padding-right: 2.5rem;
    }

    /* Style untuk ikon clear yang berada di dalam input */
    .clear-icon {
        position: absolute;
        top: 50%;
        right: 0.75rem;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 1.25rem;
        color: #aaa;
    }

    .clear-icon:hover {
        color: #000;
    }
</style>

<form action="@yield('action')">
    <div class="input-group" style="width: 40vw;">
        <input type="text" class="form-control" placeholder="Search" name="search" value="{{ request('search') }}">
        @if (request()->filled('search'))
            <span class="clear-icon" id="clearSearch">&times;</span>
        @endif
        <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
    </div>
</form>

<script>
    // Jika tombol clear ada, tambahkan event listener untuk menghapus nilai input
    document.getElementById('clearSearch')?.addEventListener('click', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            // Buat objek URL dari URL saat ini
            let url = new URL(window.location.href);
            // Hapus parameter 'search'
            url.searchParams.delete('search');
            // Redirect ke URL baru tanpa parameter search
            window.location.href = url.toString();
        }
    });
</script>
