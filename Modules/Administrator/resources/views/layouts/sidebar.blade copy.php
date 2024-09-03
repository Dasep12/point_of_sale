   <!-- sidebar menu -->
   <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
       <div class="menu_section">
           <h3 class="text-center mr-4">Warehouse<br> Manajemen System</h3>
           <ul class="nav side-menu">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Dashboard</a></li>
               <li><a><i class="fa fa-cubes"></i> Catalog <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                       <li><a href="{{ url('administrator/warehouse') }}">Warehouse</a></li>
                       <li><a href="{{ url('administrator/location') }}">Location</a></li>
                       <li><a href="{{ url('administrator/units') }}">Units</a></li>
                       <li><a href="{{ url('administrator/packaging') }}">Packaging</a></li>
                       <li><a href="{{ url('administrator/customers') }}">Customers</a></li>
                       <li><a href="{{ url('administrator/material') }}">Material</a></li>
                   </ul>
               </li>

               <li><a><i class="fa fa-money"></i> Pricing <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                       <li><a href="{{ url('administrator/handling') }}">Handling</a></li>
                       <li><a href="#">Rent Warehouse</a></li>
                       <li><a href="#">Tax</a></li>
                   </ul>
               </li>

               <li><a><i class="fa fa-exchange"></i> Manage Stock <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                       <li><a href="{{ url('administrator/inbound') }}">Inbound</a></li>
                       <li><a href="{{ url('administrator/outbound') }}">Outbound</a></li>
                   </ul>
               </li>
               <li><a><i class="fa fa-outdent"></i> Control Stock <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                       <li><a href="{{ url('administrator/summary') }}">Summary Stock</a></li>
                   </ul>
               </li>

               <li><a><i class="fa fa-file-pdf-o"></i> Reporting <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                       <li><a href="#">History</a></li>
                       <li><a href="#">Process</a></li>
                   </ul>
               </li>

               <li><a><i class="fa fa-cogs"></i> Tools <span class="fa fa-chevron-down"></span></a>
                   <ul class="nav child_menu">
                       <li><a href="#">Role Manajemen</a></li>
                       <li><a href="#">User Manajemen</a></li>
                   </ul>
               </li>


           </ul>
       </div>


   </div>
   <!-- /sidebar menu -->