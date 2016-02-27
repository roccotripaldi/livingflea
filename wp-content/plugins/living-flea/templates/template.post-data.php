<script id="shaph-template-post_data" type="text/template">
    <h3 class="shaph-title">Tell us more...</h3>
    <label>Give your post a title</label>
    <input
        type="text"
        class="shaph-extension-data text-input"
        name="postTitle"
    <% if ( extensionData.postTitle ) { %>value="<%- extensionData.postTitle %>" <% } %>/>
    <label>At which market were you?</label>
    <input
        type="text"
        class="shaph-extension-data text-input"
        name="marketName"
        id="marketName"
    <% if ( extensionData.marketName ) { %>value="<%- extensionData.marketName %>" <% } %>/>
    <div id="marketSelect">

    </div>
</script>