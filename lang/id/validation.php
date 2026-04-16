<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus berisi tanggal setelah :date.',
    'after_or_equal'       => ':attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus berisi tanggal sebelum :date.',
    'before_or_equal'      => ':attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':attribute harus antara :min dan :max.',
        'file'    => ':attribute harus antara :min dan :max kilobyte.',
        'string'  => ':attribute harus antara :min dan :max karakter.',
        'array'   => ':attribute harus antara :min dan :max item.',
    ],
    'boolean'              => 'Bidang :attribute harus bernilai true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus terdiri dari :digits angka.',
    'digits_between'       => ':attribute harus antara :min dan :max angka.',
    'dimensions'           => 'Dimensi gambar :attribute tidak valid.',
    'distinct'             => 'Bidang :attribute memiliki nilai yang duplikat.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa sebuah berkas.',
    'filled'               => 'Bidang :attribute harus memiliki nilai.',
    'gt'                   => [
        'numeric' => ':attribute harus lebih besar dari :value.',
        'file'    => ':attribute harus lebih besar dari :value kilobyte.',
        'string'  => ':attribute harus lebih besar dari :value karakter.',
        'array'   => ':attribute harus memiliki lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => ':attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'string'  => ':attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'   => ':attribute harus memiliki :value item atau lebih.',
    ],
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'max'                  => [
        'numeric' => ':attribute mungkin tidak lebih besar dari :max.',
        'file'    => ':attribute mungkin tidak lebih besar dari :max kilobyte.',
        'string'  => ':attribute mungkin tidak lebih besar dari :max karakter.',
        'array'   => ':attribute mungkin tidak memiliki lebih dari :max item.',
    ],
    'mimes'                => ':attribute harus berupa berkas berjenis: :values.',
    'mimetypes'            => ':attribute harus berupa berkas berjenis: :values.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => 'Kata sandi salah.',
    'required'             => 'Silahkan isi/pilih :attribute terlebih dahulu.',
    'string'               => ':attribute harus berupa string.',
    'unique'               => ':attribute sudah digunakan.',

    /*
    |--------------------------------------------------------------------------
    | Atribut Validasi Kustom
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'description' => 'keterangan pengeluaran',
        'amount' => 'jumlah pengeluaran',
        'employee_id' => 'karyawan',
        'id_employee' => 'karyawan',
        'store_id' => 'toko',
        'id_store' => 'toko',
        'id_expense_category' => 'kategori pengeluaran',
        'expense_date' => 'tanggal pengeluaran',
        'product_name' => 'nama produk',
        'price' => 'harga',
        'stock' => 'stok',
        'name' => 'nama',
        'email' => 'alamat email',
        'password' => 'kata sandi',
        'payment_method_id' => 'metode pembayaran'
    ],

];
