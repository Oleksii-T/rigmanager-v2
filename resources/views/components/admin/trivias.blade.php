<div class="modal fade" id="alog-trivia" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Activily log trivia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Log names and respective events explanation:
                <ol>
                    <li>
                        <b>models</b>
                        <ul>
                            <li>
                                <em>created</em>:
                                model created.
                                <small>Model attributes can be found in 'properties.attributes' field.</small>
                            </li>
                            <li>
                                <em>updated</em>:
                                model updated.
                                <small>Updated model attributes can be found in 'properties' field</small>
                            </li>
                            <li>
                                <em>deleted</em>:
                                model deleted
                            </li>
                            <li>
                                <em>view</em>:
                                model been viewed
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>emails</b>
                        <ul>
                            <li>
                                <em>send</em>:
                                email been send.
                                <small>Detailed email info can be found in 'properties' field.</small>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>import</b>
                        <ul>
                            <li>
                                <em>error-validation</em>:
                                422 validation error occured.
                                <small>Exact arror can be found in 'description' field.</small>
                            </li>
                            <li>
                                <em>importing</em>:
                                logs of importing logic
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>users</b>
                        <ul>
                            <li>
                                <em>login</em>:
                                user logged in.
                            </li>
                            <li>
                                <em>logout</em>:
                                user logged out.
                            </li>
                            <li>
                                <em>contacts</em>:
                                user contacts been requested.
                            </li>
                            <li>
                                <em>get-invoice</em>:
                                user asked for invoice.
                            </li>
                            <li>
                                <em>unauthenticated</em>:
                                unauthenticated error occured.
                                <small>
                                    Logs created due to backend-middleware will not contain any explicit cause of error,
                                    instead, see properties->general_info->url.
                                    Logs caused by JS check - will contain type of check in description field.
                                </small>
                            </li>
                            <li>
                                <em>not-subscribed</em>:
                                failed subscription check.
                                <small>
                                    Logs created due to backend-middleware will not contain any explicit cause of error,
                                    instead, see properties->general_info->url.
                                    Logs caused by JS check - will contain type of check in description field.
                                </small>
                            </li>
                            <li>
                                <em>fail-login</em>:
                                failed login attempt.
                            </li>
                            <li>
                                <em>spam-price-request-for-same-post</em>:
                                failed price request because of spam protection
                                <small>1 price request per post per day.</small>
                            </li>
                            <li>
                                <em>spam-price-request-for-same-author</em>:
                                failed price request because of spam protection
                                <small>5 price request per author per hour.</small>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>posts</b>
                        <ul>
                            <li>
                                <em>price-request</em>:
                                Price request send.
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>mailers</b>
                        <ul>
                            <li>
                                <em>email-send</em>:
                                Email been send by Mailer.
                                <small>Posts ids can be found in 'properties' field.</small>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>page-assists</b>
                        <ul>
                            <li>
                                <em>import</em>:
                                import asisst modal shown.
                            </li>
                            <li>
                                <em>importValidationErrors</em>:
                                import validation errors asisst modal shown.
                            </li>
                            <li>
                                <em>postCreation</em>:
                                post creation asisst modal shown.
                            </li>
                            <li>
                                <em>postShow</em>:
                                post show asisst modal shown.
                            </li>
                        </ul>
                    </li>
                    <li>
                        <b>feedback-bans</b>
                        <ul>
                            <li>
                                <em>catch</em>:
                                feedback from banned user been catched.
                            </li>
                        </ul>
                    </li>
                </ol>
                Causer: entity which caused the log. generally - logged in user.
                <br>
                <br>
                Subject: entiry on which log event occurred. generally - model.
                <br>
                <br>
                general_info property:
                <ul>
                    <li>
                        <em>ip</em>:
                        the IP of client
                        <small>request()->ip()</small>
                    </li>
                    <li>
                        <em>url</em>:
                        the requested url
                        <small>request()->fullUrl()</small>
                    </li>
                    <li>
                        <em>from</em>:
                        url from which request was send
                        <small>request()->headers->get('referer')</small>
                    </li>
                    <li>
                        <em>location</em>:
                        country by IP
                        <small>stevebauman/location</small>
                    </li>
                    <li>
                        <em>agent</em>:
                        user agent of client request
                        <small>request()->header('User-Agent')</small>
                    </li>
                    <li>
                        <em>agent_info</em>:
                        parsed user agent
                        <small>jenssegers/agent</small>
                    </li>
                    <li>
                        <em>request_params</em>:
                        http request params. (without _token)
                        <small>request()->all()</small>
                    </li>
                </ul>
                Activity logs role in app logic:
                <ul>
                    <li>Views  visualization</li>
                    <li>Subscription notification send tracking</li>
                    <li>Analitics</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="engagement-trivia" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Engagement trivia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <b>Engagement</b> - a measure of how much a user interacts with the site.
                It is calculated using activity logs caused by user.
                <br><br>
                <b>Engagement Percent</b> - a ration between the number of users with same/more engagement points and the number of all users.
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subcs-trivia" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Activily log trivia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Subscription logic explanation:
                <ul>
                    <li>Logic contains: "Subscription Plans", "Subscriptions" and "Subscription Cycles".</li>
                    <li>"Subscriptions" belongs to "Subscription plan".</li>
                    <li>"Subscription Cycles" belongs to "Subscription". E.g. user makes monthly sub, each month "Subscription Cycle" will be created (subscription entity stays unchanged).</li>
                    <li>Sub plans are synced with Stripe when created and updated.</li>
                    <li>Sub plans prices can not be changed via site. Only manual editting.</li>
                    <li>
                        Sub creation:<br>
                        firstly, we are creating new default payment method (with 3DS check).<br>
                        Then, subscription is created and payed via default payment method (just created).<br>
                        Pending payments are allowed - sub may be created as incomplete - 1 day user can use sub functionalities - after 1 day of incomplete sub - it will be automaticaly canceled.
                    </li>
                    <li>When user cancels sub, we leave last cycle as active - so paid functionalities are available untill last payed period expired.</li>
                    <li>If user makes new subscription when active cycle is still on, we deactivate current cycle (with respec sub) and create new sub with new active cycle.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sub-create-trivia" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manual Subscription creation trivia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                You can create subscription for Stripe manually - 'External Id' and 'First Cycle Invoice' must be filled.<br>
                Example for 'First Cycle Invoice':<br>
                {"id": "in_***", "number": "00000000-0000", "payment_intent_id": "pi_***"}<br>
                where:<br>
                "id" is Stripe Invoice ID,<br>
                "number" is Stripe Invoice Number,<br>
                "payment_intent_id" is Stripe Payment Intent ID.<br>
                All this information can be found in the Stripe dashboard.<br>
                This used to display actual information and Stripe invoice for user.<br>
                <br>
                A 'manual' subscription can be created as well. Such subscription will be extended for free without any additional checks.
            </div>
        </div>
    </div>
</div>
