  <?php

  use Illuminate\Support\Facades\DB;

  ?>

  <!-- sidebar menu -->
  <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
      <h3 class="text-center mr-4">System<br> Point Of Sale </h3>
      <ul class="nav side-menu">
        <?php
        $menuTop = DB::select("SELECT * FROM vw_sys_menu WHERE LevelNumber = 0 and user_id = '$user_id' and enable_menu = 1 ");
        ?>
        @foreach($menuTop as $mn)
        <li>
          <?php    ?>
          <a href="{{ url($mn->MenuUrl) }}"><i class="{{ $mn->MenuIcon }}"></i>
            {{ $mn->MenuName  }}</a>
        </li>
        <?php
        $mainMenu = DB::select("SELECT  * FROM vw_sys_menu WHERE LevelNumber = 1 and ParentMenu = '*' and user_id = '$user_id' and enable_menu = 1 ");
        foreach ($mainMenu as $smn) : ?>
          <li><a><i class="{{ $smn->MenuIcon }}"></i> {{ $smn->MenuName  }} <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <?php
              $subMenu = DB::select("SELECT  * FROM vw_sys_menu WHERE ParentMenu = '$smn->menu_id' and user_id = '$user_id' and enable_menu = 1 ");
              foreach ($subMenu as $smn) : ?>

                <li><a href="{{ url($smn->MenuUrl) }}">{{ $smn->MenuName  }}</a></li>
              <?php endforeach ?>
            </ul>
          </li>
        <?php endforeach ?>
        @endforeach
      </ul>
    </div>


  </div>
  <!-- /sidebar menu -->