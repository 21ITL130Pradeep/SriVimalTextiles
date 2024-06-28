<?php 
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
{   
    header('location:login.php');
}
else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="MediaCenter, Template, eCommerce">
    <meta name="robots" content="all">
    <title>Order History</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/green.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <link href="assets/css/lightbox.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/rateit.css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="assets/images/icons.ico">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body class="cnt-home">
<header class="header-style-1">
    <?php include('includes/top-header.php');?>
    <?php include('includes/main-header.php');?>
    <?php include('includes/menu-bar.php');?>
</header>

<div class="breadcrumb">
    <div class="container">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="#">Home</a></li>
                <li class='active'>Shopping Cart</li>
            </ul>
        </div>
    </div>
</div>

<div class="body-content outer-top-xs">
    <div class="container">
        <div class="row inner-bottom-sm">
            <div class="shopping-cart">
                <div class="col-md-12 col-sm-12 shopping-cart-table ">
                    <div class="table-responsive">
                        <form name="cart" method="post">	
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="cart-romove item">#</th>
                                        <th class="cart-description item">Image</th>
                                        <th class="cart-product-name item">Product Name</th>
                                        <th class="cart-qty item">Quantity</th>
                                        <th class="cart-sub-total item">Price Per unit</th>
                                        <th class="cart-sub-total item">Shipping Charge</th>
                                        <th class="cart-total item">Grandtotal</th>
                                        <th class="cart-total item">Payment Method</th>
                                        <th class="cart-description item">Order Date</th>
                                        <th class="cart-total item">Pay</th>
                                        <th class="cart-total last-item">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalAmount = 0;
                                    $query = mysqli_query($con, "SELECT products.productImage1 as pimg1, products.productName as pname, products.id as proid, orders.productId as opid, orders.quantity as qty, products.productPrice as pprice, products.shippingCharge as shippingcharge, orders.paymentMethod as paym, orders.orderDate as odate, orders.id as orderid FROM orders JOIN products ON orders.productId=products.id WHERE orders.userId='".$_SESSION['id']."' AND orders.paymentMethod IS NOT NULL");
                                    $cnt = 1;
                                    while($row = mysqli_fetch_array($query))
                                    {
                                        $qty = $row['qty'];
                                        $price = $row['pprice'];
                                        $shippcharge = $row['shippingcharge'];
                                        $subtotal = ($qty*$price) + $shippcharge;
                                        $totalAmount += $subtotal;
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt;?></td>
                                        <td class="cart-image">
                                            <a class="entry-thumbnail" href="detail.html">
                                                <img src="admin/productimages/<?php echo $row['proid'];?>/<?php echo $row['pimg1'];?>" alt="" width="84" height="146">
                                            </a>
                                        </td>
                                        <td class="cart-product-name-info">
                                            <h4 class='cart-product-description'><a href="product-details.php?pid=<?php echo $row['opid'];?>"><?php echo $row['pname'];?></a></h4>
                                        </td>
                                        <td class="cart-product-quantity">
                                            <?php echo $qty; ?>   
                                        </td>
                                        <td class="cart-product-sub-total"><?php echo $price; ?>  </td>
                                        <td class="cart-product-sub-total"><?php echo $shippcharge; ?>  </td>
                                        <td class="cart-product-grand-total"><?php echo $subtotal;?></td>
                                        <td class="cart-product-sub-total"><?php echo $row['paym']; ?>  </td>
                                        <td class="cart-product-sub-total"><?php echo $row['odate']; ?>  </td>
                                        <td>
                                            <button id="rzp-button1" class="btn btn-outline-dark btn-lg"><i class="fas fa-money-bill"></i> Pay now</button>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" onClick="popUpWindow('track-order.php?oid=<?php echo htmlentities($row['orderid']);?>');" title="Track order">Track</a>
                                        </td>
                                    </tr>
                                    <?php $cnt=$cnt+1;} ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>

<script>
    var totalAmount = '<?php echo $totalAmount; ?>';
    var options = {
        "key": "rzp_test_SvJw1Rs3BCD94d",
        "amount": totalAmount * 100, // Converting to paise
        "currency": "INR",
        "description": "Acme Corp",
        "image": "https://s3.amazonaws.com/rzp-mobile/images/rzp.jpg",
        "prefill": {
            "email": "gaurav.kumar@example.com",
            "contact": "+919900000000",
        },
        "config": {
            "display": {
                "blocks": {
                    "utib": {
                        "name": "Pay using Axis Bank",
                        "instruments": [
                            {
                                "method": "card",
                                "issuers": ["UTIB"]
                            },
                            {
                                "method": "netbanking",
                                "banks": ["UTIB"]
                            },
                        ]
                    },
                    "other": {
                        "name": "Other Payment modes",
                        "instruments": [
                            {
                                "method": "card",
                                "issuers": ["ICIC"]
                            },
                            {
                                "method": "netbanking",
                            }
                        ]
                    }
                },
                "hide": [
                    {
                        "method": "upi"
                    }
                ],
                "sequence": ["block.utib", "block.other"],
                "preferences": {
                    "show_default_blocks": false
                }
            }
        },
        "handler": function (response) {
            alert(response.razorpay_payment_id);
        },
        "modal": {
            "ondismiss": function () {
                if (confirm("Are you sure, you want to close the form?")) {
                    txt = "You pressed OK!";
                    console.log("Checkout form closed by the user");
                } else {
                    txt = "You pressed Cancel!";
                    console.log("Complete the Payment");
                }
            }
        }
    };
    var rzp1 = new Razorpay(options);
    document.getElementById('rzp-button1').onclick = function (e) {
        rzp1.open();
        e.preventDefault();
    }
</script>

<script src="assets/js/jquery-1.11.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/echo.min.js"></script>
<script src="assets/js/jquery.easing-1.3.min.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script>
<script src="assets/js/jquery.rateit.min.js"></script>
<script type="text/javascript" src="assets/js/lightbox.min.js"></script>
<script src="assets/js/bootstrap-select.min.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="switchstylesheet/switchstylesheet.js"></script>
<script>
    $(document).ready(function(){ 
        $(".changecolor").switchstylesheet( { seperator:"color"} );
        $('.show-theme-options').click(function(){
            $(this).parent().toggleClass('open');
            return false;
        });
    });

    $(window).bind("load", function() {
        $('.show-theme-options').delay(2000).trigger('click');
    });
</script>

</body>
</html>
<?php } ?>
