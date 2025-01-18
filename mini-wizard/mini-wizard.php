<?php
/*
Plugin Name: Mini Wizard
Description: A simple 4-step wizard shortcode.
Version: 1.1
Author: Your Name
*/
function mini_wizard_enqueue_scripts()
{

    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
    wp_enqueue_style('mini-wizard-style', plugin_dir_url(__FILE__) . 'css/mini-wizard.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), null, true);

    wp_enqueue_script(
        'mini-wizard-script',
        plugin_dir_url(__FILE__) . 'js/mini-wizard.js',
        array(),
        null,
        true
    );
}

add_action('wp_enqueue_scripts', 'mini_wizard_enqueue_scripts');

function render_mini_wizard($atts, $content = null)
{
    $attributes = shortcode_atts(array(
        'title' => 'Contact Info',
        'description' => 'Please provide the necessary details.',
    ), $atts);

    ob_start(); ?>
    <div class="mini-wizard">
        <div class="wizard-content wizard-step mb-5 active">
            <p class="text-muted">Step 1</p>
            <div class="wizard-header">
                <nav class="breadcrumb">
                    <nav class="breadcrumb">
                        <span class="breadcrumb-item active">Home</span>
                        <span class="breadcrumb-item">Contact Info</span>
                        <span class="breadcrumb-item">Quantity</span>
                        <span class="breadcrumb-item">Price</span>
                        <span class="breadcrumb-item">Done</span>
                    </nav>
                </nav>
            </div>
            <h2 class="mb-4">Contact Info</h2>
            <form>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">required</span></label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" placeholder="Enter your phone number">
                </div>
                <div id="error-messages"></div>
                <button type="button" class="btn btn-primary next-step">Continue</button>
            </form>
        </div>

        <!-- Шаг 2 -->
        <div class="wizard-content mb-5" id="step-2">
            <p class="text-muted">Step 2</p>
            <div class="wizard-header">
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">Home</span>
                    <span class="breadcrumb-item">Contact Info</span>
                    <span class="breadcrumb-item">Quantity</span>
                    <span class="breadcrumb-item">Price</span>
                    <span class="breadcrumb-item">Done</span>
                </nav>
            </div>
            <h2 class="mb-4">Quantity</h2>
            <form>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">required</span></label>
                    <input type="number" class="form-control" id="quantity" placeholder="Enter quantity required">
                    <div id="quantity-error" class="text-danger mt-1"></div>
                </div>
                <div id="error-messages" class="mb-3"></div>
                <div class="d-flex justify-content-start gap-2">
                    <button type="button" class="btn btn-primary next-step">Continue</button>
                    <button type="button" class="btn btn-secondary prev-step">
                        <span class="me-1">&larr;</span> Back
                    </button>
                </div>
            </form>
        </div>

        <div class="wizard-content" id="step-3">
            <p class="text-muted">Step 3</p>
            <div class="wizard-header">
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">Home</span>
                    <span class="breadcrumb-item">Contact Info</span>
                    <span class="breadcrumb-item">Quantity</span>
                    <span class="breadcrumb-item">Price</span>
                    <span class="breadcrumb-item">Done</span>
                </nav>
            </div>
            <div class="text-start">
                <h2 class="mb-2">Price</h2>
                <h3 class="fw-bold display-4" id="quantity-display">10$</h3>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-primary" id="send-email-btn">Continue</button>
                <button type="button" class="btn btn-secondary prev-step">
                    <span class="me-1">&larr;</span> Back
                </button>
            </div>
        </div>

        <div class="wizard-content">
            <p class="text-muted">Step 4</p>
            <div class="wizard-header">
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">Home</span>
                    <span class="breadcrumb-item">Contact Info</span>
                    <span class="breadcrumb-item">Quantity</span>
                    <span class="breadcrumb-item">Price</span>
                    <span class="breadcrumb-item">Done</span>
                </nav>
            </div>
            <div class="text-start">
                <h2 class="mb-2">Done</h2>
                <div class="d-flex align-items-center">
                    <span class="badge bg-success me-2">&#10004;</span>
                    <h3 class="fw-bold custom-font-size">Your email was sent successfully</h3>
                </div>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-primary">
                    Start again
                    <span class="ms-2">&#8594;</span>
                </button>
            </div>
        </div>

        <div class="wizard-content" id="step-5">
            <p class="text-muted">Step 5</p>
            <div class="wizard-header">
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">Home</span>
                    <span class="breadcrumb-item">Contact Info</span>
                    <span class="breadcrumb-item">Quantity</span>
                    <span class="breadcrumb-item">Price</span>
                    <span class="breadcrumb-item">Done</span>
                    <span class="breadcrumb-item">Error</span>
                </nav>
            </div>
            <div class="text-start">
                <h2 class="mb-2">Error</h2>
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning me-2">⚠️</span>
                    <h3 class="fw-bold custom-font-size">We cannot send you email right now. Use alternative way to contact us</h3>
                </div>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-primary">
                    Start again
                    <span class="ms-2">&#8594;</span>
                </button>
            </div>
        </div>

        <div class="wizard-description mt-4">
            <h3 class="fw-bold"><?php echo esc_html($attributes['title']); ?></h3>
            <p class="text-muted"><?php echo esc_html($attributes['description']); ?></p>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('r_test', 'render_mini_wizard');
?>
