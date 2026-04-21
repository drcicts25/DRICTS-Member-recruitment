<?php
// Database Configuration
$host = 'localhost';     
$dbname = 'ictrecruitment'; 
$username = 'root';      
$password = '';          

header('Content-Type: application/json');

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Format Interests Checkboxes array into string
        $interests_array = isset($_POST['interests']) ? $_POST['interests'] :[];
        if (in_array('Other', $interests_array) && !empty($_POST['interest_other'])) {
            $key = array_search('Other', $interests_array);
            $interests_array[$key] = 'Other: ' . strip_tags($_POST['interest_other']);
        }
        $interests_str = implode(", ", $interests_array);

        // Prepare SQL Statement (PDO Preparation prevents SQL injection natively)
        $sql = "INSERT INTO icts_registrations (
            full_name, name_initials, dob, grade_class, admission_no, address, 
            email, contact_no, whatsapp_no, guardian_name, guardian_contact, 
            has_pc, ict_level, interests, experience, reason_join, 
            time_commit, workshops, special_skills
        ) VALUES (
            :full_name, :name_initials, :dob, :grade_class, :admission_no, :address, 
            :email, :contact_no, :whatsapp_no, :guardian_name, :guardian_contact, 
            :has_pc, :ict_level, :interests, :experience, :reason_join, 
            :time_commit, :workshops, :special_skills
        )";

        $stmt = $pdo->prepare($sql);

        // Execute Bind
        $stmt->execute([
            ':full_name' => $_POST['full_name'],
            ':name_initials' => $_POST['name_initials'],
            ':dob' => $_POST['dob'],
            ':grade_class' => $_POST['grade_class'],
            ':admission_no' => $_POST['admission_no'],
            ':address' => $_POST['address'],
            ':email' => $_POST['email'],
            ':contact_no' => $_POST['contact_no'],
            ':whatsapp_no' => $_POST['whatsapp_no'],
            ':guardian_name' => $_POST['guardian_name'],
            ':guardian_contact' => $_POST['guardian_contact'],
            ':has_pc' => $_POST['has_pc'],
            ':ict_level' => $_POST['ict_level'],
            ':interests' => $interests_str,
            ':experience' => $_POST['experience'] ?? '',
            ':reason_join' => $_POST['reason_join'],
            ':time_commit' => $_POST['time_commit'],
            ':workshops' => $_POST['workshops'],
            ':special_skills' => $_POST['special_skills'] ?? ''
        ]);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Request Method']);
    }

} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>