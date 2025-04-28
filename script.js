async function likePost(blogId) {
    const response = await fetch(`handlers/like_handler.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `blog_id=${blogId}`
    });
    const result = await response.json();
    if (result.success) {
        location.reload();
    } else {
        alert(result.message);
    }
}