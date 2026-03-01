<?php
session_start();
include 'includes/header.php';
?>

<style>
/* Body Background */
body {
    background: url('images/background.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    position: relative;
}

body::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 1;
}

/* Form wrapper */
.register-wrapper {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 900px;
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 30px 40px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

/* Header */
.register-header h4 {
    text-align: center;
    color: #579b49;
    font-weight: 700;
    font-size: 1.4rem;
    text-transform: uppercase;
    margin-bottom: 8px;
    font-family: 'Orbitron', sans-serif;
}

.register-header span {
    display: block;
    font-size: 1.1rem;
    color: #579b49;
    margin-top: 3px;
    font-family: 'Poppins', sans-serif;
}

/* Inputs & Selects */
.form-control, select.form-control {
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
    border: 1px solid #ced4da;
    margin-bottom: 12px;
    width: 100%;
    box-sizing: border-box;
    height: 42px;
}

.form-control:focus, select.form-control:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 10px rgba(74,144,226,0.2);
    outline: none;
}

/* Buttons */
.btn-custom {
    border-radius: 10px;
    padding: 10px;
    font-weight: 600;
    font-size: 15px;
    color: #fff;
    background: #579b49;
    border: 1px solid #579b49;
    cursor: pointer;
    text-align: center;
    width: 100%;
    transition: background 0.3s ease, transform 0.2s ease;
}

.btn-custom:hover {
    background: #3d7b20;
    transform: translateY(-1px);
}

/* Flex container */
.flex-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
}

.flex-line a {
    color: #579b49;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
}
.flex-line a:hover {
    color: #357ABD;
}

/* Responsive row layout */
.row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.row .col-md-6 {
    flex: 1;
    min-width: 45%;
}

/* Import fonts */
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@600&family=Poppins:wght@400&display=swap');
</style>

<div class="register-wrapper">
    <?php include 'message.php'; ?>
    <div class="register-header">
        <h4>
            <span>नयाँ प्रयोगकर्ता विवरण भर्नुहोस्</span>
        </h4>
    </div>

    <form action="manual_register.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <label>कर्मचारी संख्या</label>
                <input type="text" name="personnel_no" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Rank / दर्जा</label>
                <select class="form-control" name="rank_code" required>
                    <option value='3'>महारथी</option>
                    <option value='5'>रथी</option>
                    <option value='7'>उपरथी</option>
                    <option value='9'>सहायक रथी</option>
                    <option value='10'>आ.सहायक रथी</option>
                    <option value='11'>महा सेनानी</option>
                    <option value='12'>आ.महा सेनानी</option>
                    <option value='13'>प्रमुख सेनानी</option>
                    <option value='14'>आ. प्रमुख सेनानी</option>
                    <option value='15'>सेनानी</option>
                    <option value='16'>आ.सेनानी</option>
                    <option value='17'>सह. सेनानी</option>
                    <option value='18'>आ.सह सेनानी</option>
                    <option value='19'>उप सेनानी</option>
                    <option value='21'>सहायक सेनानी</option>
                    <option value='23'>अधिकृत क्याडेट शुरु</option>
                    <option value='24'>पदिक क्याडेट</option>
                    <option value='25'>पदिक कर्मचारी क्याडेट</option>
                    <option value='26'>इन्सर्भिस क्याडेट</option>
                    <option value='27'>मानार्थ सह सेनानी</option>
                    <option value='29'>मानार्थ उप सेनानी</option>
                    <option value='31'>प्रमुख सुवेदार</option>
                    <option value='33'>सिनियर सुवेदार</option>
                    <option value='35'>सुबेदार</option>
                    <option value='37'>जमदार</option>
                    <option value='39'>गण कार्य हुद्बा</option>
                    <option value='41'>गण प्रबन्ध हुद्बा</option>
                    <option value='43'>गुल्म कार्य हुद्बा</option>
                    <option value='45'>गुल्म प्रबन्ध हुद्बा</option>
                    <option value='47'>हुद्बा</option>
                    <option value='49'>अमल्दार</option>
                    <option value='51'>प्युठ</option>
                    <option value='53'>सिपाही</option>
                    <option value='54'>एन.सि.ई.  पाँचौ स्तर</option>
                    <option value='55'>सैन्य</option>
                    <option value='56'>ए.सैन्य</option>
                    <option value='58'>एन.सि.ई. चौथो स्तर</option>
                    <option value='59'>कोते</option>
                    <option value='60'>एन.सि.ई. तेस्रो स्तर</option>
                    <option value='62'>एन.सि.ई. दोस्रो स्तर</option>
                    <option value='63'>एन.सि.ई. प्रथम स्तर</option>
                    <option value='77'>हुद्बा क्याडेट</option>
                    <option value='100'>वरिष्ठ चार्टर्ड एकाउन्टेन्ट</option>
                    <option value='101'>प्राड सहायक रथी</option>
                    <option value='102'>शाखा अधिकृत</option>
                    <option value='103'>सेनानी (अ.प्रा.)</option>
                    <option value='104'>प्रमुख सेनानी (अ.प्रा.)</option>
                    <option value='105'>नायव सुब्बा</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label>Full Name (English)</label>
                <input type="text" name="full_name_en" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>पूरा नाम (Nepali)</label>
                <input type="text" name="full_name_ne" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label>Unit / इकाई</label>
            <input type="text" id="unitSearch" class="form-control mb-2" placeholder="यूनिट खोज्नुहोस्...">
            <select class="form-control" name="unit_id" id="unitSelect" required>
                <option value='74'>अति विशिष्त व्यक्ति सुरक्षा निर्देशनालय</option>
                <option value='27'>अनुसन्धान र विस्तार निर्देशनालय</option>
                <option value='58'>असवाव निर्देशनालय</option>
                <option value='113'>अस्थायी निबेश</option>
                <option value='106'>आपुर्ति तथा परिवहन गण</option>
                <option value='86'>आपुर्ति तथा परिवहन निर्देशनालय</option>
                <option value='115'>आर्टिलरी तालिम शिक्षालय</option>
                <option value='18'>आवास तथा विमा शाखा, कल्याणकारी योजना निर्देशनालय</option>
                <option value='49'>ईन्जिनियरिङ्ग विभाग</option>
                <option value='127'>उत्तर पश्चिम पृतना</option>
                <option value='124'>उपत्यका पृतना</option>
                <option value='91'>एम.र्इ.एस. (के.शा.)</option>
                <option value='90'>एम्युनिशन कार्य महाशाखा</option>
                <option value='68'>कलेज अफ मेडिकल पोलिटेक्निक</option>
                <option value='12'>कल्याणकारी योजना निर्देशनालय</option>
                <option value='23'>कल्याणकारी स्वास्थ्य सेवा व्यवस्थापन महाशाखा</option>
                <option value='21'>का. मु. प्रधान सेनापतिको कार्यालय</option>
                <option value='136'>काठमाडौँ-तराई मधेश द्रुतमार्ग सडक आयोजना कार्यालय</option>
                <option value='46'>कार्यरथीको कार्यालय</option>
                <option value='20'>कोष तथा लेखा नियन्त्रक महाशाखा, कल्याणकारी योजना निर्देशनालय</option>
                <option value='81'>गणेशदल गण</option>
                <option value='8'>गुणस्तर नियन्त्रण निर्देशनालय</option>
                <option value='94'>गुणस्तर नियन्त्रण निर्देशनालय</option>
                <option value='67'>चिकित्सा शास्त्र महाविद्यालय</option>
                <option value='98'>छापाखाना शाखा, श्रब्यदृश्य माहाशाखा, सै.ज.नि.</option>
                <option value='76'>जगदल गण</option>
                <option value='60'>जंगी असवाव खाना</option>
                <option value='73'>जनसम्पर्क तथा सूचना निर्देशनालय</option>
                <option value='64'>टु.मु. कोतखाना डिपो</option>
                <option value='128'>त्रिभुवन आर्मी अफिसर्स क्लब</option>
                <option value='144'>नं. १ युद्धकवच गुल्म</option>
                <option value='134'>नं. १ र्इन्टेलिजेन्स गण</option>
                <option value='78'>नं. १३ बाहिनी अड्रडा</option>
                <option value='77'>नं. १४ बाहिनी अड्रडा</option>
                <option value='72'>नं. १५ बाहिनी अड्डा</option>
                <option value='79'>नं. १६ बाहिनी अड्रडा</option>
                <option value='71'>नं. १७ बाहिनी अड्रडा</option>
                <option value='143'>नयाँ गोरख गण</option>
                <option value='66'>नर्सिङ्ग महाविद्यालय</option>
                <option value='7'>निरीक्षण निर्देशनालय</option>
                <option value='51'>निरीक्षण निर्देशनालय</option>
                <option value='50'>निरीक्षणाधिकृतको कार्यालय</option>
                <option value='87'>निवेश, कार्यरथी विभाग</option>
                <option value='36'>नीति तथा योजना निर्देशनालय</option>
                <option value='43'>नीति तथा योजना महाशाखा</option>
                <option value='105'>नेपाल क्याभलरी</option>
                <option value='114'>नेपाली सेना वार कलेज</option>
                <option value='59'>नेपाली सेना स्वास्थ्य विज्ञान संस्थान</option>
                <option value='118'>नेपाली सैनिक प्रतिष्ठान</option>
                <option value='111'>पशु बिकास तथा पशु चिकित्शा निर्देशनालय</option>
                <option value='61'>पश्चिम एयर बेश</option>
                <option value='125'>पश्चिम पृतना</option>
                <option value='69'>पि.एस्.ओज. कार्यालय</option>
                <option value='120'>पूर्वी पृतना</option>
                <option value='9'>प्रधान सेनापतिको कार्यालय</option>
                <option value='47'>प्रबन्धरथीको कार्यालय</option>
                <option value='56'>प्राप्ती उपशाखा, सैनिक सामग्री प्राप्ती निर्देशनालय</option>
                <option value='44'>फौज योजना महाशाखा</option>
                <option value='53'>बजेट निर्देशनालय</option>
                <option value='28'>बलाधिकृतको कार्यालय</option>
                <option value='1'>बलाध्यक्षको कार्यालय</option>
                <option value='135'>बहु व्यवसाय उद्योग पहिरन सामाग्री शाखा</option>
                <option value='83'>भर्ना छनौट निर्देशनालय</option>
                <option value='109'>भैरव बहान गुल्म</option>
                <option value='126'>मध्य पश्चिम पृतना</option>
                <option value='122'>मध्य पूर्वी पृतना</option>
                <option value='123'>मध्य पृतना</option>
                <option value='99'>मनोबैज्ञानिक कार्य महाशाखा</option>
                <option value='3'>मानव अधिकार निर्देशनालय</option>
                <option value='55'>मिन्हा मोजारा</option>
                <option value='97'>युद्ध कवच गण</option>
                <option value='40'>युद्धकार्य निर्देशनालय</option>
                <option value='29'>युद्धकार्य महानिर्देशनालय</option>
                <option value='19'>योजना तथा अनुसन्धान महाशाखा, कल्याणकारी योजना निर्देशनालय</option>
                <option value='138'>रक्षा मन्त्रालय</option>
                <option value='41'>रणनीतिक तथा दीर्घकालीन योजना निर्देशनालय</option>
                <option value='75'>राजदल गण</option>
                <option value='14'>राष्ट्रिय निकुञ्ज तथा बन्यजन्तु आरक्ष निर्देशनालय</option>
                <option value='141'>राष्ट्रिय सुरक्षा परिषदको सचिवालय</option>
                <option value='62'>राष्ट्रिय सेवा दल</option>
                <option value='104'>रेडियो तथा प्रकाशन शाखा</option>
                <option value='132'>र्इन्जिनियर तालिम शिक्षालय</option>
                <option value='129'>वन तथा पर्यावरण सुरक्षा निर्देशनालय</option>
                <option value='101'>विकास निर्माण कार्यदल गण १</option>
                <option value='102'>विकास निर्माण कार्यदल गण २</option>
                <option value='54'>विकास निर्माण निर्देशनालय</option>
                <option value='65'>विदेश उपशाखा, सैनिक सामग्री प्राप्ती निर्देशनालय</option>
                <option value='38'>विदेश तालिम शाखा, सैनिक तालिम निर्देशनालय, सै.ता. तथा ड.म‍.नि.</option>
                <option value='96'>विद्युत तथा यान्त्रिक शिक्षालय</option>
                <option value='95'>विद्युत तथा यान्त्रिक सेवा केन्द्र</option>
                <option value='11'>विद्युत तथा यान्त्रिक सेवा निर्देशनालय</option>
                <option value='35'>विपद व्यवस्थापन निर्देशनालय</option>
                <option value='137'>विशेष फौज बाहिनी</option>
                <option value='25'>वीरेन्द्र अस्पताल</option>
                <option value='84'>वेतन बृति तथा समारोह निर्देशनालय</option>
                <option value='33'>व्यवस्था, नीति तथा योजना महानिर्देशनालय</option>
                <option value='34'>शान्ति सेना संचालन निर्देशनालय</option>
                <option value='110'>शार्दुलजंग गुल्म</option>
                <option value='6'>शिकायत जाँच निर्देशनालय</option>
                <option value='93'>शिकायत जाँच निर्देशनालय</option>
                <option value='17'>शैक्षिक शाखा, कल्याणकारी योजना निर्देशनालय</option>
                <option value='103'>श्रब्यदृश्य शाखा</option>
                <option value='48'>संभाररथीको कार्यालय</option>
                <option value='80'>समन्वय शाखा, बलाधिकृतको कार्यालय</option>
                <option value='10'>समन्वय शाखा, बलाध्यक्षको कार्यालय</option>
                <option value='100'>समारोह श्रब्यदृश्य महाशाखा</option>
                <option value='130'>सर्भे महाशाखा</option>
                <option value='4'>सर्वोत्कृष्ट अभ्यास महाशाखा</option>
                <option value='92'>सर्वोत्कृष्ट अभ्यास महाशाखा</option>
                <option value='2'>सहायक बलाध्यक्षको कार्यालय</option>
                <option value='142'>साइबर सुरक्षा निर्देशनालय</option>
                <option value='82'>सिग्नल तालिम शिक्षालय</option>
                <option value='121'>सुदुर पश्चिम पृतना</option>
                <option value='131'>सुन्दरीजल आर्सनल कार्यालय</option>
                <option value='140'>सुरक्षा तथा समारोह व्यवस्था सचिवालय, उपराष्ट्रपतिको कार्यालय</option>
                <option value='139'>सुरक्षा तथा समारोह व्यवस्था सचिवालय, राष्ट्रपति भवन</option>
                <option value='22'>सूचना तथा प्रविधि महाशाखा</option>
                <option value='5'>सेना प्राड विवाक</option>
                <option value='89'>सैनिक अभिलेखालय</option>
                <option value='70'>सैनिक आर्थिक प्रशासन विभाग</option>
                <option value='133'>सैनिक आवसिय माध्यमिक बिद्यालय, भक्तपुर</option>
                <option value='31'>सैनिक इन्टेलिजेन्स महानिर्देशनालय</option>
                <option value='39'>सैनिक ईण्टेलिजेन्स बाहिनी</option>
                <option value='117'>सैनिक कमाण्ड तथा स्टाफ कलेज</option>
                <option value='116'>सैनिक केन्द्रिय पुस्तकालय</option>
                <option value='63'>सैनिक गठ्ठाघर डिपो</option>
                <option value='30'>सैनिक तालिम तथा डक्ट्रिन महानिर्देशनालय</option>
                <option value='24'>सैनिक पुनर्स्थापना केन्द्र</option>
                <option value='88'>सैनिक प्रहरी गण</option>
                <option value='112'>सैनिक बन्दोबस्ती शिक्षालय</option>
                <option value='107'>सैनिक ब्याण्ड</option>
                <option value='42'>सैनिक शारीरिक तालिम तथा खेलकुद केन्द्र</option>
                <option value='52'>सैनिक संगठन निर्देशनालय</option>
                <option value='108'>सैनिक संग्रहालय</option>
                <option value='26'>सैनिक सचिव  विभाग</option>
                <option value='15'>सैनिक सामग्री उत्पादन तथा यान्त्रिक सेवा महानिर्देशनालय</option>
                <option value='16'>सैनिक सामग्री उत्पादन निर्देशनालय</option>
                <option value='13'>सैनिक स्वास्थ्य महानिर्देशनालय</option>
                <option value='32'>सैनिक हवाई महानिर्देशनालय</option>
                <option value='57'>सैनिक हवाई महानिर्देशनालय,मर्मत तथा सम्भार</option>
                <option value='85'>स्थपति निर्देशनालय</option>
                <option value='37'>स्वदेश तालिम शाखा, सैनिक तालिम निर्देशनालय, सै.ता. तथा ड.म‍.नि.</option>
                <option value='119'>स्वास्थ्य सेवा व्यवस्थापन महाशाखा</option>
                <option value='45'>हतियार तथा उपकरण महाशाखा</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label>Phone / सम्पर्क नम्बर</label>
                <input type="tel" name="phone" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Email / इमेल ठेगाना</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label>Password / पासवर्ड</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>पासवर्ड पुष्टि</label>
                <input type="password" name="confirmpassword" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label>Profile Image / प्रोफाइल तस्वीर</label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <div class="flex-line">
            <button type="submit" name="registerUser" class="btn btn-custom">
                <i class="fas fa-user-plus me-2"></i>दर्ता गर्नुहोस्
            </button>
            <a href="login.php">पहिल्यै खाता छ?</a>
        </div>
    </form>
</div>

<?php include 'includes/script.php'; ?>
<script src="https://kit.fontawesome.com/yourkitid.js" crossorigin="anonymous"></script>

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