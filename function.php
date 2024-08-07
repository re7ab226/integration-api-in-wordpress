////add this block in function.php////


function display_properties_list() {
    $apiEndpoint = 'https://api.endpoint'; 

    $response = wp_remote_get($apiEndpoint);

    if (is_wp_error($response)) {
        return '<p>Unable to retrieve properties at this time.</p>';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data) || isset($data['error'])) {
        return '<p>No properties found or error occurred.</p>';
    }

    $output = '<div class="properties-list">';
    foreach ($data['data'] as $property) {
        $output .= '<div class="property-item">';
        $output .= '<h3 style="text-align: center; color: #333; margin: 0; padding: 10px;">' . esc_html($property['name']) . '</h3>';
        $output .= '<p style="text-align: center; color: #333; margin: 0; padding: 10px;">Area: ' . esc_html($property['area']) . '</p>';
        if (!empty($property['images']) && is_array($property['images'])) {
            $output .= '<div class="property-images">';
            foreach ($property['images'] as $image) {
                $output .= '<img src="' . esc_url($image) . '" alt="Property Image" />';
            }
            $output .= '</div>';
        }
        $output .= '</div>';
    }
    $output .= '</div>'; // End of properties-list

    return $output;
}

function register_properties_list_shortcode() {
    add_shortcode('properties_list', 'display_properties_list');
}
add_action('init', 'register_properties_list_shortcode');
