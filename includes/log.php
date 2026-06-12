<?php

function logAktivitas($koneksi, $nama, $aktivitas)
{
    $nama = mysqli_real_escape_string(
        $koneksi,
        $nama
    );

    $aktivitas = mysqli_real_escape_string(
        $koneksi,
        $aktivitas
    );

    mysqli_query($koneksi, "
        INSERT INTO aktivitas
        (
            user,
            aksi,
            created_at
        )
        VALUES
        (
            '$nama',
            '$aktivitas',
            NOW()
        )
    ");
}
?>