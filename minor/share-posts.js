// Toggle the "like" state
function toggleLike(likeId) {
    const likeElement = document.getElementById(likeId);
    likeElement.classList.toggle('liked');
    
    if (likeElement.classList.contains('liked')) {
        likeElement.style.color = 'red';  // Like action: Red heart
    } else {
        likeElement.style.color = '';  // Remove color when not liked
    }
}

// Show/hide the comment section
function toggleComment(commentId) {
    const commentSection = document.getElementById(`comment-section${commentId.slice(-1)}`);
    const commentInput = document.getElementById(`comment-input${commentId.slice(-1)}`);
    
    // Toggle the visibility of the comment section
    if (commentSection.style.display === 'none') {
        commentSection.style.display = 'block';
        commentInput.focus();  // Focus on the textarea to enable typing
    } else {
        commentSection.style.display = 'none';
    }
}
