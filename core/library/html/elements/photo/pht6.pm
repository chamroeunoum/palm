<style type="text/css" >
    .cam_photo_h {
        position: relative;
        display: block;
        float: left;
        width: 190px;
        height: auto;
        padding: 4px;
        border: 1px solid #DDD;
        border-bottom: 1px solid #AAA;
    }
    .cam_photo {
        position: relative;
        display: block;
        float: left;
        background: #CCCCCC;
        width: 98%;
        padding: 5px 1%;
        height: 150px;
    }
    .cam_label {
        position: relative;
        display: block;
        float: left;
        width: 100%;
        padding: 5px 0px;
        text-align: center;
        height: 30px;
        line-height: 30px;
    }
</style>
<div class="cam_photo_h" >
    <div class="cam_photo" >
        <img src="<?php echo @$src; ?>" />
    </div>
    <div class="cam_label" >Camera Photo...</div>
</div>