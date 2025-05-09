<!-- Vendor JS Files -->
<script src="{{ url('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>


<!-- Template Main JS File -->
<script src="{{ url('admin/assets/js/main.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://kit.fontawesome.com/168dbc5fa4.js" crossorigin="anonymous"></script>

{{-- <script>
    function previewImage() {
        const image = document.querySelector('#image');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.src = oFREvent.target.result;
        }
    }

    function formatRupiah(el) {
        // Menghilangkan semua karakter kecuali digit
        var nilai = el.value.replace(/[^\d]/g, '');

        // Mengonversi nilai menjadi format Rupiah
        var rupiah = '';
        var angkarev = nilai.toString().split('').reverse().join('');
        for (var i = 0; i < angkarev.length; i++) {
            if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
        }
        var hasil = rupiah.split('', rupiah.length - 1).reverse().join('');
        el.value = hasil;
    }
    $(document).ready(function() {
        $('#deskripsiTour').summernote({
            placeholder: 'Deskripsi Paket Tour',
            tabsize: 2,
            height: 300,
        });
    });

    $(document).ready(function() {
        $('.select2').select2();
    });
</script> --}}
