# Codeigniter3 - a SonicIgniter Framework 🔥

We can use system folder as github submodule, for easier updates. Below guide how to do it.

# How to?

```git submodule add https://github.com/TheFrozenThrone/Codeigniter3 system/```

This will init repo settings with file `.gitmodules`

```
[submodule "Codeigniter3"]
	path = system
	url = https://github.com/TheFrozenThrone/Codeigniter3.git
```

maybe?
`GIT_SSH_COMMAND="ssh -i ssh/id_system" git submodule update --init --remote`

Repo is private, so you should use SSH Key to access repo:

`GIT_SSH_COMMAND="ssh -i ssh/id_system" git submodule init`

then update your modules ( first pull ):

`GIT_SSH_COMMAND="ssh -i ssh/id_system" git submodule update`

after, each time

`GIT_SSH_COMMAND="ssh -i /var/www/project_folder(s)/ssh/id_system" git submodule update --remote`

to push newer version to your repo:

`git submodule update --force --remote && git commit -a -m "Update Codeigniter3 Core!" && git push`

Extending feature: Class Core in lib!

For autodeploy we should use autodeploy - machine user!

Submodule authentication => https://www.deployhq.com/blog/using-submodules-in-deploy

https://developer.github.com/v3/guides/managing-deploy-keys/

### PHPSTORM - SHIT

Its not working. Totally, normaly .

![](https://i.gyazo.com/60b26ad21df10a278a6f22a8814a570d.png)

Disable this, in main project. If you want to update core , use function upper. If you want to struggle with PHPSTORM shit - try this.

Update check branches, then commit any files. Then! Maybe there will be update and phpstorm will see new system revision and you can push it!

Or try just click pull button !

![](https://i.gyazo.com/501142f2b96401adf77c341a3d159d37.png)

#### PHPSTORM - SHIT x2

In project dir edit file `.git/config` . There should be 2 links to SI .

```
[submodule "Codeigniter3"]
	url = https://github.com/TheFrozenThrone/Codeigniter3.git
	active = true
[submodule "CodeIgniter3"]
	active = true
	url = git@github.com:TheFrozenThrone/Codeigniter3.git
```
