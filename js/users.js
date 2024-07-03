window.addEventListener("DOMContentLoaded", init);

function init() {
    document.getElementById('search').addEventListener('input', function() {
        const searchValue = this.value.trim();
        fetchUsers(searchValue, currentSort);
    });

    document.querySelectorAll('.sort-option').forEach(function(option) {
        option.addEventListener('click', function() {
            const sortOption = this.getAttribute('data-sort');
            const field = this.textContent.includes('Name') ? 'user_fname' : 'negative_points';
            currentSort = { field, order: sortOption };
            fetchUsers(document.getElementById('search').value.trim(), currentSort);
        });
    });
}

let currentSort = { field: 'user_fname', order: 'asc' };

function fetchUsers(search, sort) {
    const data = { search, sort, fname: true, lname: true }; // Dodajte fname i lname kao true u objekt data
    fetch('parts/getSortedUsers.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(users => {
            renderTable(users);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching users.');
        });
}

function renderTable(users) {
    const table = document.getElementById('tableUsers');
    let html = `
        <tr>
            <th>Photo:</th>
            <th>User name:</th>
            <th>Usr email:</th>
            <th>User phone:</th>
            <th>User age:</th>
            <th>Negative points:</th>
            <th>Bann:</th>
        </tr>
    `;

    users.forEach(user => {
        html += `
            <tr style="border-bottom: 2px solid black;">
                <td><img src="photos/uploads/${user.photo}" alt="User" width="60" height="60" style="border-radius: 50px"></td>
                <td>${user.user_fname} ${user.user_lname}</td>
                <td>0${user.user_phone}</td>
                <td>${user.user_email}</td>
                <td>${user.age}</td>
                <td class="negative_points">${user.negative_points}</td>
                <td>
                    <input type="hidden" class="id_user" value="${user.id_user}">
                    <button type="button" class="submit">Submit</button>
                </td>
            </tr>
        `;
    });

    table.innerHTML = html;

    document.querySelectorAll('.submit').forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('tr');
            var id_user = row.querySelector('.id_user').value;
            var negative_points = row.querySelector('.negative_points').innerText;

            var data = {
                id_user: id_user,
                negative_points: negative_points
            };

            fetch('parts/ban_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'OK') {
                        alert('User banned successfully.');
                        row.remove();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred.');
                });
        });
    });
}
