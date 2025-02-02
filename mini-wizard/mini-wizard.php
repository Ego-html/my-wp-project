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

    static $instance_count = 0;
    $instance_count++;

    ob_start(); ?>
    <!-- Step 1 -->
    <div class="wizard-content wizard-step mb-5 active row" id="step-1">
        <div class="col-12">
            <div class="wizard-header">
                <nav class="breadcrumb custom-breadcrumb p-3 bg-white rounded" style="width: fit-content;">
                <span class="breadcrumb-item">
                     <img src="<?php echo plugins_url('images/home.png', __FILE__); ?>" alt="Home"
                          class="breadcrumb-icon" style="margin-top: -5px;">
               </span>
                    <span class="breadcrumb-item" id="breadcrumb-step-1-1">Contact Info</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-2-1">Quantity</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-3-1">Price</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-4-1">Done</span>
                </nav>
            </div>
            <h2 class="mb-4">Contact Info</h2>
            <form>
                <div class="mb-3 form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name">
                </div>

                <div class="mb-3 form-group position-relative">
                    <label for="email" class="form-label">Email</label>
                    <span class="text-danger required-label">required</span>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>

                <div class="mb-3 form-group">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" placeholder="Enter your phone number">
                </div>

                <div id="error-messages"></div>
                <button type="button" class="btn btn-primary next-step">Continue</button>
            </form>
        </div>
    </div>

    <!-- Step 2 -->
    <div class="wizard-content mb-5 row" id="step-2">
        <div class="col-12">
            <div class="wizard-header">
                <nav class="breadcrumb custom-breadcrumb p-3 bg-white rounded" style="width: fit-content;">
                <span class="breadcrumb-item" id="breadcrumb-step-1">
                     <img src="<?php echo plugins_url('images/home.png', __FILE__); ?>" alt="Home"
                          class="breadcrumb-icon" style="margin-top: -5px;">
               </span>
                    <span class="breadcrumb-item" id="breadcrumb-step-1-2">Contact Info</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-2-2">Quantity</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-3-2">Price</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-4-2">Done</span>
                </nav>
            </div>
            <h2 class="mb-4">Quantity</h2>
            <form>
                <div class="mb-3 form-group position-relative">
                    <label for="quantity" class="form-label custom-label">Quantity
                        <span class="text-danger required-second-block" style="right: 0; top: 0;">
            required
        </span>
                    </label>
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
    </div>

    <!-- Step 3 -->
    <div class="wizard-content row" id="step-3">
        <div class="col-12">
            <div class="wizard-header">
                <nav class="breadcrumb custom-breadcrumb p-3 bg-white rounded" style="width: fit-content;">
                <span class="breadcrumb-item">
                     <img src="<?php echo plugins_url('images/home.png', __FILE__); ?>" alt="Home"
                          class="breadcrumb-icon" style="margin-top: -5px;">
               </span>
                    <span class="breadcrumb-item" id="breadcrumb-step-1-3">Contact Info</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-2-3">Quantity</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-3-3">Price</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-4-3">Done</span>
                </nav>
            </div>
            <h2 class="mb-2">Price</h2>
            <h3 class="fw-bold display-4" id="quantity-display">10$</h3>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-primary" id="send-email-btn">Continue</button>
                <button type="button" id="back-button" class="btn btn-secondary prev-step">
                    <span class="me-1">&larr;</span> Back
                </button>
            </div>
        </div>
    </div>

    <!-- Step 4 -->
    <div class="wizard-content row" id="step-4">
        <div class="col-12">
            <div class="wizard-header">
                <nav class="breadcrumb custom-breadcrumb p-3 bg-white rounded" style="width: fit-content;">
                <span class="breadcrumb-item">
                    <img src="<?php echo plugins_url('images/home.png', __FILE__); ?>" alt="Home"
                         class="breadcrumb-icon" style="margin-top: -5px;">
                </span>
                    <span class="breadcrumb-item" id="breadcrumb-step-2-4">Contact Info</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-3-4">Quantity</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-5-4">Price</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-4-4">Done</span>
                </nav>
            </div>
            <div class="text-start">
                <h2 class="mb-2">Done</h2>
                <div class="d-flex align-items-center">
                    <!-- Галочка слева от текста -->
                    <span class="badge bg-success me-2">&#10004;</span>
                    <h3 class="fw-bold custom-font-size">Your email was sent successfully</h3>
                </div>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <!-- Кнопка внизу -->
                <button type="button" class="btn btn-primary start-again-btn">
                    Start again
                    <span class="ms-2">&#8594;</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Error Step -->
    <div class="wizard-content row" id="step-5">
        <div class="col-12">
            <div class="wizard-header">
                <nav class="breadcrumb custom-breadcrumb p-3 bg-white rounded" style="width: fit-content;">
                <span class="breadcrumb-item">
                     <img src="<?php echo plugins_url('images/home.png', __FILE__); ?>" alt="Home"
                          class="breadcrumb-icon" style="margin-top: -5px;">
               </span>
                    <span class="breadcrumb-item" id="breadcrumb-step-2-5">Contact Info</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-3-5">Quantity</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-4-5">Price</span>
                    <span class="breadcrumb-item" id="breadcrumb-step-5-5">Done</span>
                </nav>
            </div>
            <div class="text-start">
                <h2 class="mb-2">Error</h2>
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning me-2">⚠️</span>
                    <h3 class="fw-bold custom-font-size">We cannot send you email right now. Use alternative way to
                        contact us</h3>
                </div>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-primary start-again-btn">
                    Start again
                    <span class="ms-2">&#8594;</span>
                </button>
            </div>
        </div>
    </div>

    <div class="wizard-description mt-4">
        <h3 class="fw-bold"><?php echo esc_html($attributes['title']); ?></h3>
        <p class="text-muted"><?php echo esc_html($attributes['description']); ?></p>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('r_test', 'render_mini_wizard');
?>
