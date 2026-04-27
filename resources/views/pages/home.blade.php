@extends('layout.app')

@section('content')

<style>
    :root {
        --primary: #ff2e93;
        --secondary: #ff6bb5;
        --bg-light: #fff5fa;
        --deep: #d1006f;
    }

    html { scroll-behavior: smooth; }

    section { padding: 90px 0; }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary), var(--deep));
        color: #fff;
        border: none;
        font-weight: 600;
    }

    .btn-deep {
        background: var(--deep);
        color: #fff;
        font-weight: 600;
        border: none;
    }

    .btn-deep:hover {
        background: #a80057;
        color: #fff;
    }

    .feature-card {
        transition: 0.3s;
        border-radius: 12px;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
    }

    .section-title {
        font-weight: 700;
        margin-bottom: 15px;
    }
</style>

<!-- NAVBAR -->
<nav class="navbar sticky-top shadow-sm navbar-expand-lg navbar-light bg-white py-2">
    <div class="container">
        <a class="navbar-brand" href="#home">
            <img src="{{asset('/images/logo.png')}}" width="96px">
        </a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#why">Why Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>

            <a href="{{url('/userRegistration')}}" class="btn btn-deep ms-lg-3 mt-3 mt-lg-0 px-4 py-2">
                🚀 Start Sale
            </a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section id="home" style="background: var(--bg-light);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="fw-bold">
                    Smart POS System for Growing Businesses
                </h1>

                <p class="text-muted mt-3">
                    Manage sales, inventory, customers, and profit tracking — everything in one powerful POS system designed for real shop owners.
                </p>

                <div class="mt-4">
                    <a href="{{url('/userRegistration')}}" class="btn btn-deep px-4 py-2 me-2">🚀 Start Sale</a>
                    <a href="{{url('/userLogin')}}" class="btn bg-gradient-primary px-4 py-2">Login</a>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <img src="{{asset('/images/hero.svg')}}" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section id="about">
    <div class="container text-center">
        <h2 class="section-title">About Our POS System</h2>

        <p class="text-muted col-lg-8 mx-auto">
            This POS system is built for real business use — from small retail shops to supermarkets.
            It simplifies billing, reduces manual errors, tracks inventory automatically, and helps you understand your daily profit instantly.
        </p>
    </div>
</section>

<!-- WHY CHOOSE US (EXPANDED) -->
<section id="why" style="background: var(--bg-light);">
    <div class="container text-center">
        <h2 class="section-title">Why Choose Us?</h2>

        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white feature-card">
                    <h5>⚡ Ultra Fast Billing</h5>
                    <p class="text-muted">Create invoices in seconds with barcode support and keyboard shortcuts.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white feature-card">
                    <h5>📦 Smart Inventory</h5>
                    <p class="text-muted">Auto stock update, low stock alert, and product tracking system.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white feature-card">
                    <h5>📊 Business Reports</h5>
                    <p class="text-muted">Daily sales, profit/loss, and customer analytics dashboard.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white feature-card">
                    <h5>👥 Multi User Access</h5>
                    <p class="text-muted">Add cashier, manager, admin roles with permission control.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white feature-card">
                    <h5>💰 Expense Tracking</h5>
                    <p class="text-muted">Track daily expenses and calculate real profit automatically.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="p-4 bg-white feature-card">
                    <h5>📱 Mobile Friendly</h5>
                    <p class="text-muted">Run your full POS from mobile, tablet or desktop anytime.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- KEY FEATURES -->
<section id="features">
    <div class="container text-center">
        <h2 class="section-title">Key Modules</h2>

        <div class="row mt-5">
            <div class="col-md-3 mb-3"><div class="p-3 bg-white shadow-sm rounded">Billing System</div></div>
            <div class="col-md-3 mb-3"><div class="p-3 bg-white shadow-sm rounded">Product Management</div></div>
            <div class="col-md-3 mb-3"><div class="p-3 bg-white shadow-sm rounded">Customer CRM</div></div>
            <div class="col-md-3 mb-3"><div class="p-3 bg-white shadow-sm rounded">Reports Dashboard</div></div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS (BETTER LAYOUT) -->
<section id="testimonials" style="background: var(--bg-light);">
    <div class="container text-center">
        <h2 class="section-title">What Our Users Say</h2>

        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="{{asset('/images/man.jpg')}}" class="avatar mx-auto mb-3">
                    <h6>Rafi Ahmed</h6>
                    <small class="text-muted">Retail Shop Owner</small>
                    <p class="mt-2">“Billing is super fast. I can manage my shop even during rush hours without errors.”</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="{{asset('/images/woman.jpg')}}" class="avatar mx-auto mb-3">
                    <h6>Nusrat Jahan</h6>
                    <small class="text-muted">Boutique Business</small>
                    <p class="mt-2">“Inventory tracking changed everything. I never run out of stock now.”</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="{{asset('/images/man.jpg')}}" class="avatar mx-auto mb-3">
                    <h6>Mehedi Hasan</h6>
                    <small class="text-muted">Super Shop Manager</small>
                    <p class="mt-2">“Very smooth system. Reports help me understand daily profit easily.”</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <h2 class="section-title">Contact Us</h2>

                <p class="text-muted">
                    Want a demo or need support? We’re here to help you grow your business.
                </p>

                <p><b>Email:</b> pos_solution@gmail.com</p>
                <p><b>Phone:</b> +880 123 456 789</p>
            </div>

            <div class="col-lg-7">
                <form>
                    <input class="form-control mb-3" placeholder="Your Name">
                    <input class="form-control mb-3" placeholder="Email">
                    <textarea class="form-control mb-3" rows="5" placeholder="Message"></textarea>
                    <button class="btn btn-deep w-100 py-2">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="text-center py-4 bg-white border-top">
    <img src="{{asset('/images/logo.png')}}" width="80">
    <p class="mt-3 mb-0">© 2026 POS Solution. Built for real businesses.</p>
</footer>

@endsection
