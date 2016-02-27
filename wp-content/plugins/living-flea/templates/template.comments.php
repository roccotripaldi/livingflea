<script id="flea-template-comment" type="text/template">
<p><a href="<%- authorURL %>"><%= commentAuthor %></a> <%= commentContent %></p>
</script>

<script id="flea-template-comment-form" type="text/template">
    <input name="post-comment" class="post-comment-input" data-id="<%- postId %>" placeholder="Add a comment..." />
</script>

<script id="flea-template-comment-loading" type="text/template">
    <p class="loading">Adding comment...</p>
</script>

<script id="flea-template-login-comment" type="text/template">
    <h3 class="shaph-title">Login to Living Flea</h3>
    <p>You'll need to login to your Living Flea account to comment on photos.</p>
    <p>New comer to Living Flea? Simply login with Facebook to create your account.</p>
    <div class="facebook-login-status">
        <p><a class="fb-login-comment large fb-login-button"><img src="/wp-content/themes/original-flea/images/facebook-large.png" alt="login with facebook" /></a></p>
    </div>
</script>