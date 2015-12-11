<?php if(isset($project_id) && isset($project_ref) && isset($type) && isset($subject_id) && isset($subjectMessages)  ): ?>
<div id="subjectDescriptionMessages" ><!--style="width:300px; padding: 8px 0;"-->
    <ul class="nav nav-tabs" id="">
        <li class="active">
            <a href="#"><i class="fa fa-comment"></i> Messages</a>
        </li> 
    </ul>
    <div class="well"><!--style="overflow-y: scroll; overflow-x: hidden; height: 500px;"-->
        <ul class="nav nav-list">
            <?php    
            if(isset($subjectMessages) && count($subjectMessages)>0): 
                foreach($subjectMessages->fetchRoots() as $root):
                $options=array('root_id' => $root->getId()); 
                        include_partial('eisubjectmessage/printNode',array(
                         'project_id' => $project_id,
                         'project_ref' => $project_ref,
                         'subject_id' => $subject_id,
                         'type' => $type,
                         'level' => 0,
                         'msgs' => $subjectMessages->fetchTree($options),
                         'parent_id' => 0 )); 
                endforeach;
                
               endif; 

                    ?>
<!--            <li><label class="tree-toggler nav-header">Question 1</label>
                <ul class="nav nav-list tree">
                    <li><a href="#">Link</a> <a class="pull-right" href="#">reply</a></li>
                    <li><a href="#">Link</a> <a class="pull-right" href="#">reply</a></li>
                    <li><label class="tree-toggler nav-header">Question 1.1</label>
                        <ul class="nav nav-list tree">
                            <li><a href="#">Link</a><a class="pull-right" href="#">reply</a></li>
                            <li><a href="#">Link</a><a class="pull-right" href="#">reply</a></li>
                            <li><label class="tree-toggler nav-header">Question 1.1.1</label>
                                <ul class="nav nav-list tree">
                                    <li><a href="#">Link</a><a class="pull-right" href="#">reply</a></li>
                                    <li><a href="#">Link</a><a class="pull-right" href="#">reply</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>
            <li><label class="tree-toggler nav-header">Question 2</label>
                <ul class="nav nav-list tree">
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Link</a></li>
                    <li><label class="tree-toggler nav-header">Question 2.1</label>
                        <ul class="nav nav-list tree">
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><label class="tree-toggler nav-header">Question 2.1.1</label>
                                <ul class="nav nav-list tree">
                                    <li><a href="#">Link</a></li>
                                    <li><a href="#">Link</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><label class="tree-toggler nav-header">Question 2.2</label>
                        <ul class="nav nav-list tree">
                            <li><a href="#">Link</a></li>
                            <li><a href="#">Link</a></li>
                            <li><label class="tree-toggler nav-header">Question 2.2.1</label>
                                <ul class="nav nav-list tree">
                                    <li><a href="#">Link</a></li>
                                    <li><a href="#">Link</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>-->
        </ul>
    </div>
    <div class="modal hide fade" id="subjectMessageFormModal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3>Message</h3>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-sm btn-danger" data-dismiss="modal">Close</a>
                <button class="btn btn-sm btn-success pull-right" type="submit" id="submitSubjectMessage">
                    <i class="icon icon-ok-circle"></i> Send 
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>



<div class="col-lg-12 discussions">

                        <ul>

                            <li>
                                <div class="author">
                                    <img src="/assets/img/avatar.jpg" alt="avatar">
                                </div>

                                <div class="name">Łukasz Holeczek</div>
                                <div class="date">Today, 1:08 PM</div>
                                <div class="delete"><i class="fa fa-times"></i></div>

                                <div class="message">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                </div>	

                                <ul>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar3.jpg" alt="avatar">
                                        </div>
                                        <div class="name">Ann Kovalsky</div>
                                        <div class="date">Today, 1:08 PM</div>
                                        <div class="delete"><i class="fa fa-times"></i></div>

                                        <div class="message">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                        </div>

                                    </li>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar6.jpg" alt="avatar">
                                        </div>
                                        <div class="name">Megan Abbott</div>
                                        <div class="date">Today, 1:08 PM</div>
                                        <div class="delete"><i class="fa fa-times"></i></div>

                                        <div class="message">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                        </div>	
                                    </li>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar.jpg" alt="avatar">
                                        </div>
                                        <textarea class="diss-form form-control" placeholder="Write comment"></textarea>
                                    </li>

                                </ul>	

                            </li>

                            <li>
                                <div class="author">
                                    <img src="/assets/img/avatar.jpg" alt="avatar">
                                </div>

                                <div class="name">Łukasz Holeczek</div>
                                <div class="date">Today, 1:08 PM</div>
                                <div class="delete"><i class="fa fa-times"></i></div>

                                <div class="message row">
                                    <div class="col-sm-3 col-xs-6">
                                        <img src="/assets/img/gallery/photo2.jpg" class="img-responsive img-thumbnail">
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <img src="/assets/img/gallery/photo3.jpg" class="img-responsive img-thumbnail">
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <img src="/assets/img/gallery/photo4.jpg" class="img-responsive img-thumbnail">
                                    </div>
                                    <div class="col-sm-3 col-xs-6">
                                        <img src="/assets/img/gallery/photo5.jpg" class="img-responsive img-thumbnail">
                                    </div>	
                                </div>	

                                <ul>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar.jpg" alt="avatar">
                                        </div>
                                        <textarea class="diss-form form-control" placeholder="Write comment"></textarea>
                                    </li>

                                </ul>	

                            </li>

                            <li>
                                <div class="author">
                                    <img src="/assets/img/avatar9.jpg" alt="avatar">
                                </div>

                                <div class="name">Tom Allen</div>
                                <div class="date">Today, 1:08 PM</div>
                                <div class="delete"><i class="fa fa-times"></i></div>

                                <div class="message">
                                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                </div>	

                                <ul>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar2.jpg" alt="avatar">
                                        </div>
                                        <div class="name">Katie Moss</div>
                                        <div class="date">Today, 1:08 PM</div>
                                        <div class="delete"><i class="fa fa-times"></i></div>

                                        <div class="message">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                        </div>

                                    </li>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar4.jpg" alt="avatar">
                                        </div>
                                        <div class="name">Anna Holn</div>
                                        <div class="date">Today, 1:08 PM</div>
                                        <div class="delete"><i class="fa fa-times"></i></div>

                                        <div class="message">
                                            Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
                                        </div>	
                                    </li>

                                    <li>
                                        <div class="author">
                                            <img src="/assets/img/avatar9.jpg" alt="avatar">
                                        </div>
                                        <textarea class="diss-form form-control" placeholder="Write comment"></textarea>
                                    </li>

                                </ul>	

                            </li>

                        </ul>	

                    </div><!--/col-->