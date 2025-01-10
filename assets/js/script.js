// script.js

document.addEventListener('DOMContentLoaded', function() {
    const addAuthorBtn = document.getElementById('add_author');
    const authorList = document.getElementById('author_list');
    let authorCount = 1;

    addAuthorBtn.addEventListener('click', function() {
        authorCount++;

        const newAuthorDiv = document.createElement('div');
        newAuthorDiv.classList.add('author_entry');
        
        newAuthorDiv.innerHTML = `
            <input type="text" name="authors[]" placeholder="Author Name" required>
            <select name="roles[]">
                <option value="1">Main Author</option>
                <option value="2">Co-author</option>
            </select>
            <button type="button" class="remove_author">Remove</button>
        `;

        authorList.appendChild(newAuthorDiv);

        const removeAuthorBtns = document.querySelectorAll('.remove_author');
        removeAuthorBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                authorList.removeChild(newAuthorDiv);
            });
        });
    });

    const form = document.getElementById('add_paper_form');
    form.addEventListener('submit', function(event) {
        // Kiểm tra dữ liệu nhập ở đây (ví dụ: độ dài tiêu đề, kiểm tra số lượng tác giả...)

        // Ví dụ kiểm tra độ dài tiêu đề
        const titleInput = document.getElementById('title');
        if (titleInput.value.length < 5) {
            alert('Title must be at least 5 characters long.');
            event.preventDefault(); // Ngăn không submit form
        }

        // Các kiểm tra khác có thể thêm ở đây

    });
});
