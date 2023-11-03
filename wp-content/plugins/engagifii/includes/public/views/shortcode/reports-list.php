

<style> 
 .tabbs {
     display: flex;
     padding-bottom: 40px;
 }

 .tabb {
     padding: 10px 20px;
     background-color: #f0f0f0;
     margin-right: 10px;
     cursor: pointer;
     border-radius: 10px;
 }

 .tabb.active {
     background-color: #002473;
     color: white !important;
 }
 .tabb.active a {
    color: white !important;
 }
 .tabb a {
    color: #54595F !important;
 }

 .tabb-content .tabb-pane {
     display: none;
 }

 .tabb-content .tabb-pane.active {
     display: block;
 }
 .separator {
     border-bottom: 1px solid #ccc;
     margin: 8px 0 8px 0;
 }
 img.img-viewdetail {
         height: 60%;
 }
</style>
<?php
$activetabb = isset($_GET['tabb']) ? $_GET['tabb'] : 1;
?>
<div class="tabbs">
    <div class="tabb <?php echo ($activetabb == 1) ? 'active' : ''; ?>" data-tabb="1">
        <a href="#">Tab 1</a>
    </div>
    <div class="tabb <?php echo ($activetabb == 2) ? 'active' : ''; ?>" data-tabb="2">
        <a href="#">Tab 2</a>
    </div>
    <div class="tabb <?php echo ($activetabb == 3) ? 'active' : ''; ?>" data-tabb="3">
        <a href="#">Tab 3</a>
    </div>
</div>

<div class="tabb-content">
    <div class="tabb-pane <?php echo ($activetabb == 1) ? 'active' : ''; ?>" id="tabb-1">
    <?php echo do_shortcode('[legislative-reports]'); ?>
        <p>This is the content for Tab 1</p>
    </div>
    <div class="tabb-pane <?php echo ($activetabb == 2) ? 'active' : ''; ?>" id="tabb-2">
    <?php echo do_shortcode('[legislation-list]'); ?>
    </div>
    <div class="tabb-pane <?php echo ($activetabb == 3) ? 'active' : ''; ?>" id="tabb-3">
        <p>This is the content for Tab 3</p>
    </div>
</div>

<script>
    const tabbs = document.querySelectorAll('.tabbs .tabb');
    const tabbContents = document.querySelectorAll('.tabb-content .tabb-pane');

    tabbs.forEach(tabb => {
        tabb.addEventListener('click', () => {
            const tabbNumber = tabb.getAttribute('data-tabb');
            tabbs.forEach(t => t.classList.remove('active'));
            tabbContents.forEach(content => content.classList.remove('active'));

            tabb.classList.add('active');
            document.getElementById(`tabb-${tabbNumber}`).classList.add('active');
        });
    });
</script>
