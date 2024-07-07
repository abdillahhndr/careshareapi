<?php
// Include library Midtrans
require_once 'vendor/autoload.php';

// Set header untuk mengizinkan CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");

// Set server key Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-8O8xFkI3HZoh6S6TKQ75trso';
// Set environment ke Development/Sandbox (default). Set ke true untuk Production Environment.
\Midtrans\Config::$isProduction = false;
// Set sanitization ke true (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction untuk kartu kredit ke true
\Midtrans\Config::$is3ds = true;

// Menerima data dari permintaan POST
$data = json_decode(file_get_contents('php://input'), true);

// Cetak data yang diterima (untuk debugging)
// echo "Data Received:";
// var_dump($data);

// Memeriksa apakah data diterima sesuai dengan yang diharapkan
if (isset($data['user_id']) && isset($data['item']) && isset($data['mentor_id']) && isset($data['price'])) {
    $user_id = $data['user_id'];
    $item = $data['item'];
    $mentor_id = $data['mentor_id'];
    $price = $data['price'];
    // echo "Data Received:";
    // var_dump($data);
    
    // Membuat nomor order acak
    $order_id = rand();

    // Menyiapkan parameter transaksi
    $item_details = [
        [
            'id' => $item['id'],
            'price' => $item['price'],
            'quantity' => 1, // Assuming quantity is always 1 for a subscription
            'name' => $item['name'],
        ]
    ];

    // Menghitung total gross amount
    $gross_amount = $price;

    $params = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => $gross_amount,
        ],
        'item_details' => $item_details
    ];

    // Mendapatkan Snap Token dari Midtrans Snap
    $snapToken = \Midtrans\Snap::getSnapToken($params);

    // Menyimpan data transaksi ke dalam tabel 'subscriptions'
    $conn = new mysqli("localhost", "root", "", "db_careshare");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // $created_at = date('Y-m-d H:i:s');
    // $updated_at = date('Y-m-d H:i:s');
    $sql = "INSERT INTO subscriptions (id, user_id, mentor_id, price, status, snap_token)
            VALUES ('$order_id', '$user_id', '$mentor_id', '$price', 'pending', '$snapToken')";

    if ($conn->query($sql) === TRUE) {
        // Kirim respons JSON dengan snap token
        $response = [
            'snap_token' => $snapToken,
            'user_id' => $user_id,
            'order_id' => $order_id
        ];

        echo json_encode($response);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid data provided";
}
?>
