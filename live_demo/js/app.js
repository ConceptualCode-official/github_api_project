$(function () {
    const $userInput = $("#githubUser"),
          $searchBtn = $("#searchBtn"),
          $loader = $("#loader"),
          $profile = $("#profile"),
          $repoTitle = $("#repoTitle"),
          $repoList = $("#repoList");

    const showLoader = () => $loader.removeClass("hidden");
    const hideLoader = () => $loader.addClass("hidden");

    const resetUI = () => {
        $profile.addClass("hidden").html("");
        $repoList.html("");
        $repoTitle.addClass("hidden");
    };

    const showError = msg => {
        $profile
            .html(`<div class="repo-card" style="border-left-color:#e74c3c">${msg}</div>`)
            .removeClass("hidden");
    };

    // Fetch API
    const fetchData = username => $.when(
        $.getJSON(`api/getUser/index.php?user=${username}`),
        $.getJSON(`api/getRepos/index.php?user=${username}`)
    );

    // Render user
    const renderUser = user => {
        $profile.html(`
            <img src="${user.avatar_url}" alt="User">
            <div class="info">
                <h2>${user.name || user.login}</h2>
                <p>@${user.login}</p>
                <p>${user.bio || "No bio available"}</p>
                <p>Followers: ${user.followers} • Following: ${user.following}</p>
                <p>Public Repos: ${user.public_repos}</p>
            </div>
        `).removeClass("hidden");
    };

    // Render repos
    const renderRepos = repos => {
        $repoTitle.removeClass("hidden");

        if (!repos.length) {
            $repoList.html(`<div class="repo-card">No public repositories found</div>`);
            return;
        }

        $repoList.html(
            repos.map(r => `
                <div class="repo-card">
                    <h3>${r.name}</h3>
                    <a href="${r.html_url}" target="_blank">View Repository →</a>
                </div>
            `).join("")
        );
    };

    // Main Search function
    const search = () => {
        const username = $userInput.val().trim();
        if (!username) return;

        resetUI();
        showLoader();

        fetchData(username)
            .done((userRes, repoRes) => {
                hideLoader();

                const user = userRes[0];   // FIXED
                const repos = repoRes[0];  // FIXED
                if (!user || user.message === "Not Found") {
                    showError("User not found!");
                    return;
                }

                renderUser(user);
                renderRepos(repos);
            })
            .fail((e) => {
                hideLoader();
                showError("Error fetching data. Try again.");
            });
    };

    // Triggers
    $searchBtn.on("click", search);
    $userInput.on("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault(); // input submit issue fix
        search();
    }
});
});