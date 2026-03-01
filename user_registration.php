<?php
  include 'includes/authentication.php';
  include 'supporter/permissions.php';
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

  <!-- Add Modal -->
  <div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="exampleModalLabel">प्रयोगकर्ता थप्नुहोस्</h1>
      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="registration_code.php" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label for="personnel_no">कर्मचारी संख्या</label>
              <input type="text" name="personnel_no" id="personnel_no" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Rank</label>
              <select class="form-control" name="rank_code" required>
              <option value='3'>महारथी</option><option value='5'>रथी</option><option value='7'>उपरथी</option><option value='9'>सहायक रथी</option><option value='10'>आ.सहायक रथी</option><option value='11'>महा सेनानी</option><option value='12'>आ.महा सेनानी</option><option value='13'>प्रमुख सेनानी</option><option value='14'>आ. प्रमुख सेनानी</option><option value='15'>सेनानी</option><option value='16'>आ.सेनानी</option><option value='17'>सह. सेनानी</option><option value='18'>आ.सह सेनानी</option><option value='19'>उप सेनानी</option><option value='21'>सहायक सेनानी</option><option value='23'>अधिकृत क्याडेट शुरु</option><option value='24'>पदिक क्याडेट</option><option value='25'>पदिक कर्मचारी क्याडेट</option><option value='26'>इन्सर्भिस क्याडेट</option><option value='27'>मानार्थ सह सेनानी</option><option value='29'>मानार्थ उप सेनानी</option><option value='31'>प्रमुख सुवेदार</option><option value='33'>सिनियर सुवेदार</option><option value='35'>सुबेदार</option><option value='37'>जमदार</option><option value='39'>गण कार्य हुद्बा</option><option value='41'>गण प्रबन्ध हुद्बा</option><option value='43'>गुल्म कार्य हुद्बा</option><option value='45'>गुल्म प्रबन्ध हुद्बा</option><option value='47'>हुद्बा</option><option value='49'>अमल्दार</option><option value='51'>प्युठ</option><option value='53'>सिपाही</option><option value='54'>एन.सि.ई.  पाँचौ स्तर</option><option value='55'>सैन्य</option><option value='56'>ए.सैन्य</option><option value='58'>एन.सि.ई. चौथो स्तर</option><option value='59'>कोते</option><option value='60'>एन.सि.ई. तेस्रो स्तर</option><option value='62'>एन.सि.ई. दोस्रो स्तर</option><option value='63'>एन.सि.ई. प्रथम स्तर</option><option value='77'>हुद्बा क्याडेट</option><option value='100'>वरिष्ठ चार्टर्ड एकाउन्टेन्ट</option><option value='101'>प्राड सहायक रथी</option><option value='102'>शाखा अधिकृत</option><option value='103'>सेनानी (अ.प्रा.)</option><option value='104'>प्रमुख सेनानी (अ.प्रा.)</option><option value='105'>नायव सुब्बा</option>                                        </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="full_name_en">पूरा नाम (English)</label>
              <input type="text" name="full_name_en" id="full_name_en" class="form-control" placeholder="" required>
            </div>
            <div class="col-md-6">
              <label for="full_name_ne">पूरा नाम (Nepali)</label>
              <input type="text" name="full_name_ne" id="full_name_ne" class="form-control" placeholder="" required>
            </div>
          </div>

           <div class="form-group">
             <label>Unit</label>
             <input type="text" id="unitSearch" class="form-control mb-2" placeholder="यूनिट खोज्नुहोस्...">
             <select class="form-control" name="unit_id" id="unitSelect" required>
                <option value='74'>अति विशिष्त व्यक्ति सुरक्षा निर्देशनालय</option><option value='27'>अनुसन्धान र विस्तार निर्देशनालय</option><option value='58'>असवाव निर्देशनालय</option><option value='113'>अस्थायी निबेश</option><option value='106'>आपुर्ति तथा परिवहन गण</option><option value='86'>आपुर्ति तथा परिवहन निर्देशनालय</option><option value='115'>आर्टिलरी तालिम शिक्षालय</option><option value='18'>आवास तथा विमा शाखा, कल्याणकारी योजना निर्देशनालय</option><option value='49'>ईन्जिनियरिङ्ग विभाग</option><option value='127'>उत्तर पश्चिम पृतना</option><option value='124'>उपत्यका पृतना</option><option value='91'>एम.र्इ.एस. (के.शा.)</option><option value='90'>एम्युनिशन कार्य महाशाखा</option><option value='68'>कलेज अफ मेडिकल पोलिटेक्निक</option><option value='12'>कल्याणकारी योजना निर्देशनालय</option><option value='23'>कल्याणकारी स्वास्थ्य सेवा व्यवस्थापन महाशाखा</option><option value='21'>का. मु. प्रधान सेनापतिको कार्यालय</option><option value='136'>काठमाडौँ-तराई मधेश द्रुतमार्ग सडक आयोजना कार्यालय</option><option value='46'>कार्यरथीको कार्यालय</option><option value='20'>कोष तथा लेखा नियन्त्रक महाशाखा, कल्याणकारी योजना निर्देशनालय</option><option value='81'>गणेशदल गण</option><option value='8'>गुणस्तर नियन्त्रण निर्देशनालय</option><option value='94'>गुणस्तर नियन्त्रण निर्देशनालय</option><option value='67'>चिकित्सा शास्त्र महाविद्यालय</option><option value='98'>छापाखाना शाखा, श्रब्यदृश्य माहाशाखा, सै.ज.नि.</option><option value='76'>जगदल गण</option><option value='60'>जंगी असवाव खाना</option><option value='73'>जनसम्पर्क तथा सूचना निर्देशनालय</option><option value='64'>टु.मु. कोतखाना डिपो</option><option value='128'>त्रिभुवन आर्मी अफिसर्स क्लब</option><option value='144'>नं. १ युद्धकवच गुल्म</option><option value='134'>नं. १ र्इन्टेलिजेन्स गण</option><option value='78'>नं. १३ बाहिनी अड्रडा</option><option value='77'>नं. १४ बाहिनी अड्रडा</option><option value='72'>नं. १५ बाहिनी अड्डा</option><option value='79'>नं. १६ बाहिनी अड्रडा</option><option value='71'>नं. १७ बाहिनी अड्रडा</option><option value='143'>नयाँ गोरख गण</option><option value='66'>नर्सिङ्ग महाविद्यालय</option><option value='7'>निरीक्षण निर्देशनालय</option><option value='51'>निरीक्षण निर्देशनालय</option><option value='50'>निरीक्षणाधिकृतको कार्यालय</option><option value='87'>निवेश, कार्यरथी विभाग</option><option value='36'>नीति तथा योजना निर्देशनालय</option><option value='43'>नीति तथा योजना महाशाखा</option><option value='105'>नेपाल क्याभलरी</option><option value='114'>नेपाली सेना वार कलेज</option><option value='59'>नेपाली सेना स्वास्थ्य विज्ञान संस्थान</option><option value='118'>नेपाली सैनिक प्रतिष्ठान</option><option value='111'>पशु बिकास तथा पशु चिकित्शा निर्देशनालय</option><option value='61'>पश्चिम एयर बेश</option><option value='125'>पश्चिम पृतना</option><option value='69'>पि.एस्.ओज. कार्यालय</option><option value='120'>पूर्वी पृतना</option><option value='9'>प्रधान सेनापतिको कार्यालय</option><option value='47'>प्रबन्धरथीको कार्यालय</option><option value='56'>प्राप्ती उपशाखा, सैनिक सामग्री प्राप्ती निर्देशनालय</option><option value='44'>फौज योजना महाशाखा</option><option value='53'>बजेट निर्देशनालय</option><option value='28'>बलाधिकृतको कार्यालय</option><option value='1'>बलाध्यक्षको कार्यालय</option><option value='135'>बहु व्यवसाय उद्योग पहिरन सामाग्री शाखा</option><option value='83'>भर्ना छनौट निर्देशनालय</option><option value='109'>भैरव बहान गुल्म</option><option value='126'>मध्य पश्चिम पृतना</option><option value='122'>मध्य पूर्वी पृतना</option><option value='123'>मध्य पृतना</option><option value='99'>मनोबैज्ञानिक कार्य महाशाखा</option><option value='3'>मानव अधिकार निर्देशनालय</option><option value='55'>मिन्हा मोजारा</option><option value='97'>युद्ध कवच गण</option><option value='40'>युद्धकार्य निर्देशनालय</option><option value='29'>युद्धकार्य महानिर्देशनालय</option><option value='19'>योजना तथा अनुसन्धान महाशाखा, कल्याणकारी योजना निर्देशनालय</option><option value='138'>रक्षा मन्त्रालय</option><option value='41'>रणनीतिक तथा दीर्घकालीन योजना निर्देशनालय</option><option value='75'>राजदल गण</option><option value='14'>राष्ट्रिय निकुञ्ज तथा बन्यजन्तु आरक्ष निर्देशनालय</option><option value='141'>राष्ट्रिय सुरक्षा परिषदको सचिवालय</option><option value='62'>राष्ट्रिय सेवा दल</option><option value='104'>रेडियो तथा प्रकाशन शाखा</option><option value='132'>र्इन्जिनियर तालिम शिक्षालय</option><option value='129'>वन तथा पर्यावरण सुरक्षा निर्देशनालय</option><option value='101'>विकास निर्माण कार्यदल गण १</option><option value='102'>विकास निर्माण कार्यदल गण २</option><option value='54'>विकास निर्माण निर्देशनालय</option><option value='65'>विदेश उपशाखा, सैनिक सामग्री प्राप्ती निर्देशनालय</option><option value='38'>विदेश तालिम शाखा, सैनिक तालिम निर्देशनालय, सै.ता. तथा ड.म‍.नि.</option><option value='96'>विद्युत तथा यान्त्रिक शिक्षालय</option><option value='95'>विद्युत तथा यान्त्रिक सेवा केन्द्र</option><option value='11'>विद्युत तथा यान्त्रिक सेवा निर्देशनालय</option><option value='35'>विपद व्यवस्थापन निर्देशनालय</option><option value='137'>विशेष फौज बाहिनी</option><option value='25'>वीरेन्द्र अस्पताल</option><option value='84'>वेतन बृति तथा समारोह निर्देशनालय</option><option value='33'>व्यवस्था, नीति तथा योजना महानिर्देशनालय</option><option value='34'>शान्ति सेना संचालन निर्देशनालय</option><option value='110'>शार्दुलजंग गुल्म</option><option value='6'>शिकायत जाँच निर्देशनालय</option><option value='93'>शिकायत जाँच निर्देशनालय</option><option value='17'>शैक्षिक शाखा, कल्याणकारी योजना निर्देशनालय</option><option value='103'>श्रब्यदृश्य शाखा</option><option value='48'>संभाररथीको कार्यालय</option><option value='80'>समन्वय शाखा, बलाधिकृतको कार्यालय</option><option value='10'>समन्वय शाखा, बलाध्यक्षको कार्यालय</option><option value='100'>समारोह श्रब्यदृश्य महाशाखा</option><option value='130'>सर्भे महाशाखा</option><option value='4'>सर्वोत्कृष्ट अभ्यास महाशाखा</option><option value='92'>सर्वोत्कृष्ट अभ्यास महाशाखा</option><option value='2'>सहायक बलाध्यक्षको कार्यालय</option><option value='142'>साइबर सुरक्षा निर्देशनालय</option><option value='82'>सिग्नल तालिम शिक्षालय</option><option value='121'>सुदुर पश्चिम पृतना</option><option value='131'>सुन्दरीजल आर्सनल कार्यालय</option><option value='140'>सुरक्षा तथा समारोह व्यवस्था सचिवालय, उपराष्ट्रपतिको कार्यालय</option><option value='139'>सुरक्षा तथा समारोह व्यवस्था सचिवालय, राष्ट्रपति भवन</option><option value='22'>सूचना तथा प्रविधि महाशाखा</option><option value='5'>सेना प्राड विवाक</option><option value='89'>सैनिक अभिलेखालय</option><option value='70'>सैनिक आर्थिक प्रशासन विभाग</option><option value='133'>सैनिक आवसिय माध्यमिक बिद्यालय, भक्तपुर</option><option value='31'>सैनिक इन्टेलिजेन्स महानिर्देशनालय</option><option value='39'>सैनिक ईण्टेलिजेन्स बाहिनी</option><option value='117'>सैनिक कमाण्ड तथा स्टाफ कलेज</option><option value='116'>सैनिक केन्द्रिय पुस्तकालय</option><option value='63'>सैनिक गठ्ठाघर डिपो</option><option value='30'>सैनिक तालिम तथा डक्ट्रिन महानिर्देशनालय</option><option value='24'>सैनिक पुनर्स्थापना केन्द्र</option><option value='88'>सैनिक प्रहरी गण</option><option value='112'>सैनिक बन्दोबस्ती शिक्षालय</option><option value='107'>सैनिक ब्याण्ड</option><option value='42'>सैनिक शारीरिक तालिम तथा खेलकुद केन्द्र</option><option value='52'>सैनिक संगठन निर्देशनालय</option><option value='108'>सैनिक संग्रहालय</option><option value='26'>सैनिक सचिव  विभाग</option><option value='15'>सैनिक सामग्री उत्पादन तथा यान्त्रिक सेवा महानिर्देशनालय</option><option value='16'>सैनिक सामग्री उत्पादन निर्देशनालय</option><option value='13'>सैनिक स्वास्थ्य महानिर्देशनालय</option><option value='32'>सैनिक हवाई महानिर्देशनालय</option><option value='57'>सैनिक हवाई महानिर्देशनालय,मर्मत तथा सम्भार</option><option value='85'>स्थपति निर्देशनालय</option><option value='37'>स्वदेश तालिम शाखा, सैनिक तालिम निर्देशनालय, सै.ता. तथा ड.म‍.नि.</option><option value='119'>स्वास्थ्य सेवा व्यवस्थापन महाशाखा</option><option value='45'>हतियार तथा उपकरण महाशाखा</option>   
              </select>   
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="phone">संपर्क नम्बर</label>
              <input type="tel" name="phone" id="phone" class="form-control" placeholder="" required>
            </div>

            <div class="col-md-6">
              <label for="email">इमेल ठेगाना</label>
              <span class="email_error text-danger ml-2"></span>
              <input type="email" name="email" id="email" class="form-control email_id" placeholder="" required>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <label for="">पासवर्ड</label>
              <input type="password" name="password" class="form-control" placeholder="" required>
            </div>
            <div class="col-md-6">
              <label for="">पासवर्ड पुष्टिकरण</label>
              <input type="password" name="confirmpassword" class="form-control" placeholder="" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label for="">प्रोफाइल  Image</label>
              <input type="file" name="image" required><br><br>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="addUser" class="btn btn-primary">Save changes</button>
        </div>
    </form>
    </div>
    </div>
  </div>

  <!-- Delete User Modal -->
  <div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="registration_code.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="delete_id" class="delete_user_id">
          <p>Are you sure you want to delete this user?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="DeleteUserBtn" class="btn btn-danger">Yes, Delete!</button>
        </div>
    </form>
    </div>
    </div>
  </div>

  <!-- Registered Users Table -->
  <section class="content">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <?php
            include 'message.php';
          ?>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">प्रयोगकर्ता सूची</h3>
              <a href="#" data-toggle="modal" data-target="#AddUserModal" class="btn btn-primary btn-sm float-end">प्रयोगकर्ता थप्नुहोस्</a>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                  <th>SN</th>
                  <th>कर्मचारी संख्या</th>
                  <th>Rank</th>
                  <th>नाम</th>
                  <th>Unit</th>
                  <th>सम्पर्क नम्बर</th>
                  <th>इमेल ठेगाना</th>
                  <th>Created At</th>
                  <th>Status</th>
                  <th>Report</th>
                  <?php if (is_super_admin()): ?>
                  <th>Edit</th>
                  <th>Delete</th>
                  <?php endif; ?>
                  <th>Reset</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $count = 0;
                    $query = "SELECT 
                                users.personnel_no,
                                users.full_name_ne,
                                units.name_nepali AS unit_name,
                                users.phone,
                                users.email,
                                users.created_at,
                                users.is_active,
                                users.is_report,
                                users.id,
                                ranks.name_nepali AS rank_name
                              FROM users
                              LEFT JOIN ranks ON users.rank_code = ranks.rank_code
                              LEFT JOIN units ON users.unit_id = units.unit_id
                              WHERE users.is_void = 0";  // Added condition to only show non-deleted users
                    $result = mysqli_query($con, $query);

                    $query_run = mysqli_query($con, $query);
                    if (mysqli_num_rows($query_run) > 0) {
                       foreach ($query_run as $row) {
                         ?>
                         <tr>
                           <td><?= ++$count ?></td>
                           <td><?php echo $row['personnel_no']; ?></td>
                           <td><?php echo $row['rank_name']; ?></td>
                           <td><?php echo $row['full_name_ne']; ?></td>
                           <td><?php echo $row['unit_name']; ?></td>
                           <td><?php echo $row['phone']; ?></td>
                           <td><?php echo $row['email']; ?></td>
                           <td><?php echo $row['created_at']; ?></td>

                           <td>
                              <?php
                                if ($row['is_active'] == 1) {
                                    echo '<button type="button" class="btn btn-success btn-sm deactivate_btn" style="margin-left:5px;" onclick="window.location.href=\'registration_code.php?deactivate_id=' . htmlspecialchars($row['id']) . '\'">Active</button>';
                                } else {
                                    echo '<button type="button" class="btn btn-danger btn-sm activate_btn" style="margin-left:5px;" onclick="window.location.href=\'registration_code.php?activate_id=' . htmlspecialchars($row['id']) . '\'">Inactive</button>';
                                }
                              ?>
                           </td>
                           <td>
                              <?php
                                if ($row['is_report'] == 1) {
                                    echo '<button type="button" class="btn btn-success btn-sm deactivate_btn" style="margin-left:5px;" onclick="window.location.href=\'registration_code.php?deactivate_report_id=' . htmlspecialchars($row['id']) . '\'">Active</button>';
                                } else {
                                    echo '<button type="button" class="btn btn-danger btn-sm activate_btn" style="margin-left:5px;" onclick="window.location.href=\'registration_code.php?activate_report_id=' . htmlspecialchars($row['id']) . '\'">Inactive</button>';
                                }
                              ?>
                           </td>
                           <?php if (is_super_admin()): ?>
                           <td>
                             <a href="registered_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                           </td>
                           <td> 
                            <button type="button" class="btn btn-danger btn-sm delete_btn" value="<?= $row['id']; ?>">Delete</button>
                           </td>

                            <?php endif; ?>
                          <td>
                              <button type="button" class="btn btn-secondary btn-sm reset_btn" style="margin-left:5px;" 
                                  onclick="window.location.href='registration_code.php?reset_id=<?= htmlspecialchars($row['id']) ?>'">
                                  Reset
                              </button>
                          </td>
                         </tr>
                         <?php
                       }
                    } else {
                      echo '<tr><td colspan="12">No Record Found</td></tr>';
                    }
                  ?>
                 </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/script.php'; ?>

<script>
  // check email ajax
  $(document).on('keyup','.email_id',function(){
    var email = $(this).val();
    $.ajax({
      url: 'code.php',
      type: 'POST',
      data: { check_Emailbtn: true, email: email },
      success: function(response) {
        $('.email_error').html(response);
      }
    });
  });

  // delete modal popup
  $(document).on('click','.delete_btn',function(e){
    e.preventDefault();
    var user_id = $(this).val();
    $('.delete_user_id').val(user_id);
    $('#DeleteModal').modal('show');
  });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const unitSelect = document.getElementById("unitSelect");
    const unitSearch = document.getElementById("unitSearch");

    if (!unitSelect || !unitSearch) {
        console.error("Required elements not found: unitSelect or unitSearch");
        return;
    }

    const originalOptions = Array.from(unitSelect.options);

    // Filter dropdown while typing
    unitSearch.addEventListener("keyup", function() {
        const keyword = this.value.toLowerCase().trim();
        unitSelect.innerHTML = "";
        if (keyword === "") {
            // Show all if empty
            originalOptions.forEach(opt => unitSelect.appendChild(opt.cloneNode(true)));
        } else {
            originalOptions.forEach(opt => {
                if (opt.text.toLowerCase().includes(keyword)) {
                    unitSelect.appendChild(opt.cloneNode(true));
                }
            });
        }
        unitSelect.style.display = "block";
    });

    // On option selection
    unitSelect.addEventListener("change", function() {
        const selectedOption = this.options[this.selectedIndex];
        unitSearch.value = selectedOption.text;
        unitSelect.style.display = "none";
    });

    // Auto-complete on blur
    unitSearch.addEventListener("blur", function() {
        const typed = this.value.toLowerCase().trim();
        if (!typed) return;

        const match = originalOptions.find(opt => 
            opt.text.toLowerCase().includes(typed)
        );

        if (match) {
            unitSearch.value = match.text;
            unitSelect.value = match.value; // ✅ Critical: set the actual value for form submission
        }
        unitSelect.style.display = "none";
    });
});
</script>

