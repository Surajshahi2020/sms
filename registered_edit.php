<?php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
include 'config/dbcon.php';
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               <!-- /Write header content -->
            </div>
        </div>
    </div>

    <section class="content">
    <div class="container">
        <?php
        if (isset($_SESSION['status'])) {
            echo '<h4 class="alert alert-success">' . $_SESSION['status'] . '</h4>';
            unset($_SESSION['status']);
        }
        ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit - Registered Users</h3>
                <a href="user_registration.php" class="btn btn-danger btn-sm float-right">Back</a>
            </div>
            <div class="card-body">
                <form action="registration_code.php" method="POST" enctype="multipart/form-data">
                    <?php
                    if (isset($_GET['id'])) {
                        $user_id = mysqli_real_escape_string($con, $_GET['id']);
                        $query = "SELECT 
                                    users.personnel_no,
                                    users.full_name_en,
                                    users.full_name_ne,
                                    units.name_nepali AS unit_name,
                                    users.phone,
                                    users.email,
                                    users.role_as,
                                    users.is_active,
                                    users.id,
                                    users.rank_code,
                                    users.unit_id
                                  FROM users
                                  LEFT JOIN ranks ON users.rank_code = ranks.rank_code
                                  LEFT JOIN units ON users.unit_id = units.unit_id
                                  WHERE users.id = '$user_id'";

                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) {
                            $row = mysqli_fetch_assoc($query_run);
                    ?>
                        <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="personnel_no">कर्मचारी संख्या</label>
                                <input type="text" name="personnel_no" id="personnel_no" value="<?php echo $row['personnel_no']; ?>" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>Rank</label>
                                <select class="form-control" name="rank_code" required>
                                    <?php
                                    // All ranks
                                    $ranks = [
                                        3 => 'महारथी', 5 => 'रथी', 7 => 'उपरथी', 9 => 'सहायक रथी',
                                        10 => 'आ.सहायक रथी', 11 => 'महा सेनानी', 12 => 'आ.महा सेनानी',
                                        13 => 'प्रमुख सेनानी', 14 => 'आ. प्रमुख सेनानी', 15 => 'सेनानी',
                                        16 => 'आ.सेनानी', 17 => 'सह. सेनानी', 18 => 'आ.सह सेनानी',
                                        19 => 'उप सेनानी', 21 => 'सहायक सेनानी', 23 => 'अधिकृत क्याडेट शुरु',
                                        24 => 'पदिक क्याडेट', 25 => 'पदिक कर्मचारी क्याडेट', 26 => 'इन्सर्भिस क्याडेट',
                                        27 => 'मानार्थ सह सेनानी', 29 => 'मानार्थ उप सेनानी', 31 => 'प्रमुख सुवेदार',
                                        33 => 'सिनियर सुवेदार', 35 => 'सुबेदार', 37 => 'जमदार', 39 => 'गण कार्य हुद्बा',
                                        41 => 'गण प्रबन्ध हुद्बा', 43 => 'गुल्म कार्य हुद्बा', 45 => 'गुल्म प्रबन्ध हुद्बा',
                                        47 => 'हुद्बा', 49 => 'अमल्दार', 51 => 'प्युठ', 53 => 'सिपाही', 54 => 'एन.सि.ई.  पाँचौ स्तर',
                                        55 => 'सैन्य', 56 => 'ए.सैन्य', 58 => 'एन.सि.ई. चौथो स्तर', 59 => 'कोते',
                                        60 => 'एन.सि.ई. तेस्रो स्तर', 62 => 'एन.सि.ई. दोस्रो स्तर', 63 => 'एन.सि.ई. प्रथम स्तर',
                                        77 => 'हुद्बा क्याडेट', 100 => 'वरिष्ठ चार्टर्ड एकाउन्टेन्ट', 101 => 'प्राड सहायक रथी',
                                        102 => 'शाखा अधिकृत', 103 => 'सेनानी (अ.प्रा.)', 104 => 'प्रमुख सेनानी (अ.प्रा.)',
                                        105 => 'नायव सुब्बा',
                                    ];

                                    foreach ($ranks as $code => $name) {
                                        $selected = ($row['rank_code'] == $code) ? 'selected' : '';
                                        echo "<option value='{$code}' {$selected}>{$name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="full_name_en">पूरा नाम (English)</label>
                                <input type="text" name="full_name_en" id="full_name_en" value="<?php echo $row['full_name_en']; ?>" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="full_name_ne">पूरा नाम (Nepali)</label>
                                <input type="text" name="full_name_ne" id="full_name_ne" value="<?php echo $row['full_name_ne']; ?>" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Unit</label>
                                <select class="form-control" name="unit_id" required>
                                    <?php
                                    // All units
                                    $units = [
                                        74 => 'अति विशिष्त व्यक्ति सुरक्षा निर्देशनालय', 27 => 'अनुसन्धान र विस्तार निर्देशनालय',
                                        58 => 'असवाव निर्देशनालय', 113 => 'अस्थायी निबेश', 106 => 'आपुर्ति तथा परिवहन गण',
                                        86 => 'आपुर्ति तथा परिवहन निर्देशनालय', 115 => 'आर्टिलरी तालिम शिक्षालय', 18 => 'आवास तथा विमा शाखा, कल्याणकारी योजना निर्देशनालय',
                                        49 => 'ईन्जिनियरिङ्ग विभाग', 127 => 'उत्तर पश्चिम पृतना', 124 => 'उपत्यका पृतना', 91 => 'एम.र्इ.एस. (के.शा.)',
                                        90 => 'एम्युनिशन कार्य महाशाखा', 68 => 'कलेज अफ मेडिकल पोलिटेक्निक', 12 => 'कल्याणकारी योजना निर्देशनालय',
                                        23 => 'कल्याणकारी स्वास्थ्य सेवा व्यवस्थापन महाशाखा', 21 => 'का. मु. प्रधान सेनापतिको कार्यालय', 136 => 'काठमाडौँ-तराई मधेश द्रुतमार्ग सडक आयोजना कार्यालय',
                                        46 => 'कार्यरथीको कार्यालय', 20 => 'कोष तथा लेखा नियन्त्रक महाशाखा, कल्याणकारी योजना निर्देशनालय', 81 => 'गणेशदल गण',
                                        8 => 'गुणस्तर नियन्त्रण निर्देशनालय', 94 => 'गुणस्तर नियन्त्रण निर्देशनालय', 67 => 'चिकित्सा शास्त्र महाविद्यालय', 98 => 'छापाखाना शाखा, श्रब्यदृश्य माहाशाखा, सै.ज.नि.',
                                        76 => 'जगदल गण', 60 => 'जंगी असवाव खाना', 73 => 'जनसम्पर्क तथा सूचना निर्देशनालय', 64 => 'टु.मु. कोतखाना डिपो',
                                        128 => 'त्रिभुवन आर्मी अफिसर्स क्लब', 144 => 'नं. १ युद्धकवच गुल्म', 134 => 'नं. १ र्इन्टेलिजेन्स गण',
                                        78 => 'नं. १३ बाहिनी अड्रडा', 77 => 'नं. १४ बाहिनी अड्रडा', 72 => 'नं. १५ बाहिनी अड्डा',
                                        79 => 'नं. १६ बाहिनी अड्रडा', 71 => 'नं. १७ बाहिनी अड्रडा', 143 => 'नयाँ गोरख गण', 66 => 'नर्सिङ्ग महाविद्यालय',
                                        7 => 'निरीक्षण निर्देशनालय', 51 => 'निरीक्षण निर्देशनालय', 50 => 'निरीक्षणाधिकृतको कार्यालय',
                                        87 => 'निवेश, कार्यरथी विभाग', 36 => 'नीति तथा योजना निर्देशनालय', 43 => 'नीति तथा योजना महाशाखा',
                                        105 => 'नेपाल क्याभलरी', 114 => 'नेपाली सेना वार कलेज', 59 => 'नेपाली सेना स्वास्थ्य विज्ञान संस्थान',
                                        118 => 'नेपाली सैनिक प्रतिष्ठान', 111 => 'पशु बिकास तथा पशु चिकित्शा निर्देशनालय', 61 => 'पश्चिम एयर बेश',
                                        125 => 'पश्चिमी बटालियन', 42 => 'पुर्नरज्जन पदबन्दी'
                                    ];

                                    foreach ($units as $unit_id => $unit_name) {
                                        $selected = ($row['unit_id'] == $unit_id) ? 'selected' : '';
                                        echo "<option value='{$unit_id}' {$selected}>{$unit_name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="role_as">Role</label>
                                <select name="role_as" id="role_as" class="form-control" required>
                                    <option value="0" <?php echo ($row['role_as'] == 0) ? 'selected' : ''; ?>>User</option>
                                    <option value="1" <?php echo ($row['role_as'] == 1) ? 'selected' : ''; ?>>Admin</option>
                                    <option value="2" <?php echo ($row['role_as'] == 2) ? 'selected' : ''; ?>>SuperAdmin</option>
                                </select>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="phone">संपर्क नम्बर</label>
                                <input type="tel" name="phone" id="phone" value="<?php echo $row['phone']; ?>" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email">इमेल ठेगाना</label>
                                <input type="email" name="email" id="email" value="<?php echo $row['email']; ?>" class="form-control" required>
                            </div>
                        </div>
                    <?php
                        } else {
                            echo "<h5>No Record Found</h5>";
                        }
                    }
                    ?>
                    <div class="modal-footer" style="margin-top: 20px; padding-top: 10px;">
                        <button type="submit" name="updateUser" class="btn btn-info">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

</div>

<?php
include 'includes/footer.php';
include 'includes/script.php';
?>
