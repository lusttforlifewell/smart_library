<?php

session_start();

require_once __DIR__ . '/../../config/midtrans.php';
include __DIR__ . '/../../includes/header.php';

$cart = $_SESSION['cart'] ?? [];

$total = 0;

?>

<div class="max-w-6xl mx-auto py-10">

    <h1 class="text-4xl font-bold mb-8">
        Cart Ebook
    </h1>

    <?php if(count($cart) > 0): ?>

        <div class="space-y-6">

            <?php foreach($cart as $item): ?>

                <?php

                $qty = isset($item['qty']) ? $item['qty'] : 1;

                $subtotal = $item['harga'] * $qty;

                $total += $subtotal;

                ?>

                <div class="bg-white shadow rounded-2xl p-5 flex items-center justify-between">

                    <!-- KIRI -->
                    <div class="flex items-center gap-5">

                        <!-- COVER -->
                        <img
                            src="<?= BASE_URL ?>assets/img/<?php echo $item['cover']; ?>"
                            onerror="this.src='<?= BASE_URL ?>assets/img/no-image.png'"
                            class="w-24 h-32 object-cover rounded-lg border"
                        >

                        <!-- DETAIL -->
                        <div>

                            <h2 class="text-2xl font-bold">
                                <?php echo $item['judul']; ?>
                            </h2>

                            <p class="text-blue-600 font-semibold text-xl mt-2">

                                Rp <?php echo number_format($item['harga'],0,',','.'); ?>

                            </p>

                            <!-- QTY -->
                            <div class="flex items-center gap-3 mt-4">

                                <!-- MINUS -->
                                <a
                                    href="update_cart.php?id=<?php echo $item['id']; ?>&action=minus"
                                    class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center text-xl font-bold"
                                >
                                    -
                                </a>

                                <span class="text-2xl font-bold">
                                    <?php echo $qty; ?>
                                </span>

                                <!-- PLUS -->
                                <a
                                    href="update_cart.php?id=<?php echo $item['id']; ?>&action=plus"
                                    class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center text-xl font-bold"
                                >
                                    +
                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- KANAN -->
                    <div class="text-right">

                        <p class="text-slate-500">
                            Subtotal
                        </p>

                        <h1 class="text-3xl font-bold text-green-600 mt-2">

                            Rp <?php echo number_format($subtotal,0,',','.'); ?>

                        </h1>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

        <!-- TOTAL -->
        <div class="bg-white shadow rounded-2xl p-6 mt-10 flex items-center justify-between">

            <div>

                <p class="text-slate-500 text-lg">
                    Total Pembayaran
                </p>

                <h1 class="text-4xl font-bold text-blue-600 mt-2">

                    Rp <?php echo number_format($total,0,',','.'); ?>

                </h1>

            </div>

            <button
                onclick="checkoutCart()"
                class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-2xl text-lg font-bold"
            >

                Checkout Sekarang

            </button>

        </div>

    <?php else: ?>

        <div class="bg-white shadow rounded-2xl p-10 text-center">

            <h1 class="text-2xl font-bold">
                Cart kosong 
            </h1>

        </div>

    <?php endif; ?>

</div>

<script
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="<?= htmlspecialchars(\Midtrans\Config::$clientKey, ENT_QUOTES, 'UTF-8') ?>">
</script>

<script>

function checkoutCart(){

    fetch('<?= BASE_URL ?>modules/ebooks/checkout.php', {

        method: 'POST',
        credentials: 'same-origin'

    })

    .then(async res => {
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (error) {
            throw new Error('Invalid JSON response from checkout.php:\n' + text);
        }
    })

    .then(data => {

        if(data.token){

            snap.pay(data.token, {

                onSuccess: function(result){

                    fetch('save_transaction.php')
                    .then(() => fetch('clear_cart.php'))
                    .then(() => {

                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Successful!',
                            text: 'Your ebook has been unlocked successfully.'
                        }).then(() => {
                            location.reload();
                        });

                    });

                },

                onPending: function(result){

                    console.log(result);

                },

                onError: function(result){

                    console.log(result);

                    alert(JSON.stringify(result, null, 2));

                }

            });

        }

    });

}

</script>