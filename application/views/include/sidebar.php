<div class="cl-toggle"><i class="fa fa-bars"></i></div>
<div class="cl-navblock">
<div class="menu-space">
  <div class="content">
    <ul class="cl-vnavigation">
    <?php foreach ($this->cache->memcached->is_supported() ? $this->cache->memcached->get(Z_NCA.UID) : $_SESSION[Z_NCA.UID] as $kc => $c):?>
      <li><a href="<?php echo site_url($kc.'/index')?>"><i class="fa <?php echo $c['param']?>"></i><span><?php echo $c['name']?></span></a>
		<ul class="sub-menu">
		  <?php foreach ($c['child'] as $ka => $a):?>
		  <li class="<?php echo ($kc == $this->uri->segment(1) && $ka == $this->uri->segment(2)) ? 'active' : '' ?>"><a href="<?php echo site_url($kc.'/'.$ka)?>"><?php echo $a['name']?></a></li>
		  <?php endforeach;?>
		</ul>
      </li>
    <?php endforeach;?>
    </ul>
  </div>
</div>
<div class="text-right collapse-button">
  <button id="sidebar-collapse" class="btn btn-default" style=""><i style="color:#fff;" class="fa fa-angle-left"></i></button>
</div>
</div>