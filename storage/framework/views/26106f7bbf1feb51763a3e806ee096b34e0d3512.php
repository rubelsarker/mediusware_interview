<?php $__env->startSection('content'); ?>
    <div class="container-fluid app-body settings-page">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 style="text-transform: capitalize;font-weight: bold;">Recent posts sent to buffer</h4>
                    </div>
                    <div class="panel-body">
                        <form action="<?php echo e(url('history')); ?>" method="get" id="filterForm">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-3">
                                        <i class="fa fa-search" aria-hidden="true" style="position:absolute;top: 18px;left: 20px;"></i>
                                        <input class="form-control" type="text" placeholder="Search"
                                               aria-label="Search" name="search" id="searchInput" value="<?php echo e(isset($_GET['search'])?$_GET['search']:''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <span style="color: red;position:absolute;top: -15px;right: 10px;font-size: 25px;cursor: pointer;" title="Clear Date" id="dateClear">&times;</span>
                                        <input class="form-control" type="date" id="dateFilter" value="<?php echo e(isset($_GET['date'])?$_GET['date']:''); ?>" name="date" style="height: 53px;">
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" name="group_type" style="height: 53px;" id="filterGroup">
                                            <option>All Group</option>
                                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($type->type); ?>" <?php if(isset($_GET['group_type'])): ?> <?php if($_GET['group_type'] == $type->type): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e(ucwords($type->type)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered">
                            <thead>
                            <tr style="background-color: #eeeeee">
                                <th>Group Name</th>
                                <th>Group Type</th>
                                <th>Account Name</th>
                                <th>Post Text</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody id="tableData">
                            <?php
                                $page_count=count($histories);
                                $pagination = 1;
                                $current = null;
                                if(isset($_GET['page'])){
                                    $current = $_GET['page'];
                                    $histories = $histories[($current-1)];
                                } else {
                                    $histories = isset($histories[0])?$histories[0]:[];
                                }
                            ?>
                            <?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($history['group_name']); ?></td>
                                    <td><?php echo e($history['group_type']); ?></td>
                                    <td style="text-align: center;">
                                        <div style="position:relative;width: 50px;margin: 0 auto;">
                                            <span style="position: absolute; top: 0; right: 0; height: 15px;width: 15px;border-radius: 7.5px;background-color: #ffffff;color: <?php echo e($history['icon_color']); ?>" class="fa fa-<?php echo e($history['social_icon']); ?>"></span>
                                            <img src="<?php echo e($history['avatar']); ?>" width="50" class="media-object img-circle" alt="">
                                        </div>
                                    </td>
                                    <td><?php echo e($history['post_text']); ?></td>
                                    <td><?php echo e($history['time']); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php
                            $current_url = url(Request::getRequestUri());
                            $position = strpos($current_url,'page');
                            if ($position>0){
                                $current_url = substr($current_url,0,$position).'&';
                            } else {
                                $current_url .= '?';
                            }
                        ?>
                        <nav aria-label="Page navigation example" style="float: right;">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($current_url); ?>page=<?php echo e(($current-1)); ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <?php for($pagination;$pagination<=$page_count;$pagination++): ?>
                                    <li class="page-item"><a class="page-link <?php if($pagination == $current): ?> active <?php endif; ?>" href="<?php echo e($current_url); ?>page=<?php echo e($pagination); ?>"><?php echo e($pagination); ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo e($current_url); ?>page=<?php echo e(($current+1)); ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $('#filterGroup').change(function () {
            console.log('Changed');
            $('#filterForm').submit();
        });
        $('#dateFilter').focusout(function () {
            // console.log('Changed');
            $('#filterForm').submit();
        });
        $('#dateClear').click(function () {
            $('#dateFilter').val('');
            $('#filterForm').submit();
        });
        $(document).ready(function(){
            $("#searchInput").on("keyup", search);
        });
        function search() {
            var value = $(this).val().toLowerCase();
            $("#tableData tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
        function first_search() {
            var value = $('#searchInput').val().toLowerCase();
            $("#tableData tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        }
        window.onload = first_search();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>