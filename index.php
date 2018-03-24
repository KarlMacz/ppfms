<?php
    require_once('backend/database.php');
    require_once('backend/functions.php');

    include_once('layouts/header.php');
?>
<nav class="navbar navbar-default navbar-fixed-top no-margin">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand">Bhagi's International Trading Corporation</div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#home-section">Home</a></li>
                <li><a href="#about-us-section">About Us</a></li>
                <li><a href="#contact-us-section">Contact Us</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </div>
</nav>
<section id="home-section" class="hero full-height">
    <div class="hero-content">
        <div class="container">
            <div class="columns is-centered">
                <div class="column has-text-centered">
                    <div class="hero-title"><?php echo COMPANY_NAME; ?></div>
                    <p class="is-size-4">A company with a diversified portfolio of retail and distribution businesses. With over half a century of experience, the company now has a distribution network of over 1,300 dealers nationwide and still counting.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="about-us-section" class="hero">
    <div class="hero-content">
        <div class="container">
            <div class="content">
                <div class="hero-title">About Us</div>
                <p class="is-size-4">BITC prides itself as being more than just a business dealing with importation and distribution of products.  It treats brands as their own, nurturing and growing them to reach their full market potential.  The company goes beyond basic expectations and takes a proactive role in ensuring that each brand handled has a strong market presence</p>
                <p class="is-size-4">In its portfolio are trendy and high fashion brands of cosmetics and fragrances from Europe and USA such as Essence, Catrice, Flormar, Deborah Milano, and Jordana.</p> 
                <p class="is-size-4">Likewise, the company distribute technically designed small domestic appliances under American Heritage logo.</p>
                <p class="is-size-4">The latest addition to its brand portfolio is Beko Home Appliance.  One of the leading Home Appliance brands in Europe that manufactures refrigerators, washing machines, cooking ranges, small domestic appliances and air conditioners among wide range of home solution products that is widely distributed globally.</p>
            </div>
        </div>
    </div>
</section>
<section id="contact-us-section" class="hero">
    <div class="hero-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 text-center">
                    <img src="img/logo.png" style="width: 80%;">
                </div>
                <div class="col-sm-8">
                    <div class="is-size-2">Contact Us</div>
                    <hr>
                    <address class="is-size-4">
                        Kampri Bldg, 2254 Don Chino Roces Avenue<br>
                        Makati City, Metro Manila<br>
                        Phone: (02) 894-2028
                    </address>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="new-footer has-text-grey is-light">
    <div class="container">
        <div class="col-sm-6 text-left">
            <span>© Copyright <?php echo date('Y') . ' ' . COMPANY_NAME; ?>.</span>
        </div>
        <div class="col-sm-6 text-right">
            <span>All Rights Reserved.</span>
        </div>
    </div>
</footer>
<?php
    include_once('layouts/footer.php');
?>
