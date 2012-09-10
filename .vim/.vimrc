" An example for a vimrc file.
"
" Maintainer:	Bram Moolenaar <Bram@vim.org>
" Last change:	2008 Dec 17
"
" To use it, copy it to
"     for Unix and OS/2:  ~/.vimrc
"	      for Amiga:  s:.vimrc
"  for MS-DOS and Win32:  $VIM\_vimrc
"	    for OpenVMS:  sys$login:.vimrc

" When started as "evim", evim.vim will already have done these settings.
if v:progname =~? "evim"
  finish
endif

" Use Vim settings, rather than Vi settings (much better!).
" This must be first, because it changes other options as a side effect.
set nocompatible

" allow backspacing over everything in insert mode
set backspace=indent,eol,start

if has("vms")
  set nobackup		" do not keep a backup file, use versions instead
else
  set backup		" keep a backup file
endif
set history=50		" keep 50 lines of command line history
set ruler		" show the cursor position all the time
set showcmd		" display incomplete commands
set incsearch		" do incremental searching

" For Win32 GUI: remove 't' flag from 'guioptions': no tearoff menu entries
" let &guioptions = substitute(&guioptions, "t", "", "g")

" Don't use Ex mode, use Q for formatting
map Q gq

" CTRL-U in insert mode deletes a lot.  Use CTRL-G u to first break undo,
" so that you can undo CTRL-U after inserting a line break.
inoremap <C-U> <C-G>u<C-U>

" In many terminal emulators the mouse works just fine, thus enable it.
if has('mouse')
  set mouse=a
endif

" Switch syntax highlighting on, when the terminal has colors
" Also switch on highlighting the last used search pattern.
if &t_Co > 2 || has("gui_running")
  syntax on
  set hlsearch
endif

" Only do this part when compiled with support for autocommands.
if has("autocmd")

  " Enable file type detection.
  " Use the default filetype settings, so that mail gets 'tw' set to 72,
  " 'cindent' is on in C files, etc.
  " Also load indent files, to automatically do language-dependent indenting.
  filetype plugin indent on

  " Put these in an autocmd group, so that we can delete them easily.
  augroup vimrcEx
  au!

  " For all text files set 'textwidth' to 78 characters.
  autocmd FileType text setlocal textwidth=78

  " When editing a file, always jump to the last known cursor position.
  " Don't do it when the position is invalid or when inside an event handler
  " (happens when dropping a file on gvim).
  " Also don't do it when the mark is in the first line, that is the default
  " position when opening a file.
  autocmd BufReadPost *
    \ if line("'\"") > 1 && line("'\"") <= line("$") |
    \   exe "normal! g`\"" |
    \ endif

  augroup END

else

  set autoindent		" always set autoindenting on

endif " has("autocmd")

" Convenient command to see the difference between the current buffer and the
" file it was loaded from, thus the changes you made.
" Only define it when not defined already.
if !exists(":DiffOrig")
  command DiffOrig vert new | set bt=nofile | r # | 0d_ | diffthis
		  \ | wincmd p | diffthis
endif



"=================================================================
"个人设置开始
"=================================================================

"更新 vimrc 配置
" Edit the vimrc file
map <leader>vimrc :source /home/ziikii/.vimrc<cr>
map <leader>ev :e /home/ziikii/.vimrc<CR>
map <leader>sv :so /home/ziikii/.vimrc<CR>

"文件类型自动识别
filetype plugin on
filetype indent on

"语法高亮
syntax enable

"自动对齐
set autoindent

"设置自动缩进
set ai!

""智能对齐方式
set smartindent

""总是显示状态栏status line
set laststatus=2

""实现CTRL-S保存操作
imap <C-S> <Esc>:w<CR>

""实现了CTRL-C、CTRL-V复制粘贴操作的映射
nmap <C-C> yy
" inoremap <C-V> :set paste<cr><S-Ins>
" inoremap <c-v> <esc>"+p<CR>i

"不使用swap文件
set noswapfile

"设置行号
set number

"设置鼠标模式
set mouse=a

"显示模式
set showmode

"关闭自动备份
set nobackup

"搜索忽略大小写 
set ic 

"modifiable
set modifiable

"折叠代码方式
set fdm=marker
"设置语法折叠
" set foldmethod=syntax


"设置无备份文件
set writebackup

"自动进入当前编辑文件所在的目录
map <leader>cd :cd %:p:h<cr>

" -----------------------------------------------------------------
" 显示回车换行符等特殊符号	
" -----------------------------------------------------------------
" 来转换种文件格式
" set fileformat=unix

" 在默认情况下，Vim认为文件是由行组成的，并且文件最后一行是以<EOL>为结束符的。如果你想设置文件以<EOL>结束符结尾，则可以用以下命令：
" set endofline

" 如果你想设置文件不以<EOL>结束符来结尾，则可以使用以下命令：
set noendofline

"如果你使用以下命令进入 <list mode>，那么就可以清楚的看到以“$”表示的换行符和以“^I”表示的制表符。 
" set list

" 你可以使用以下命令退出<list mode>：
set nolist

" :%s/\n//g	"删除换行符
" set textwidth	"设置行宽
" set textwidth	"设置行边距
" join	"合并多行
" J	"合并两行





"------------------------------------------------------
"nt 打开NERDTree
"------------------------------------------------------
map nt :NERDTree<CR>
"是否默认显示隐藏文件
let NERDTreeShowHidden=1
"是否默认显示行号
let NERDTreeShowLineNumbers=1
"是否默认显示书签列表
let NERDTreeShowBookmarks = 1
"打开文件后是否关闭NerdTree窗口
let NERDTreeQuitOnOpen = 0
"让Tree把自己给装饰得多姿多彩漂亮点
" let NERDChristmasTree=1
 "让注释符与语句之间留一个空格
let NERDSpaceDelims=1 
"多行注释时样子更好看
let NERDCompactSexyComs=1  
"------------------------------------------------------
"配色方案
"------------------------------------------------------
colorscheme molokai
let g:molokai_original = 1

"enable 256 colors in vim 
set t_Co=256

"设置粘贴
"set paste

"行线
set cursorline

"解决中文乱码问题
set encoding=utf-8
"if has(“win32″)
"set fileencoding=chinese
"else
set fileencodings=utf-8,chinese,latin-1
" set fileencoding=utf-8
"endif

"手动设置折叠
" set foldmethod=manual 

" 'compatible' is not set
set nocompatible

"matchit.vim
" plugin - matchit.vim   对%命令进行扩展使得能在嵌套标签和语句之间跳转
" " % 正向匹配      g% 反向匹配
" " [% 定位块首     ]% 定位块尾
let loaded_matchit = 1

 "匹配括号的规则，增加针对html的<>
 set matchpairs=(:),{:},[:],<:>

"设置自定义的<leader>快捷键
" let mapleader=","
" let g:mapleader=","

"clearing highlighted search
nmap <silent> <leader>/ :nohlsearch<CR>

" Code folding options
" nmap <leader>f0 :set foldlevel=0<CR>
" nmap <leader>f1 :set foldlevel=1<CR>
" nmap <leader>f2 :set foldlevel=2<CR>
" nmap <leader>f3 :set foldlevel=3<CR>
" nmap <leader>f4 :set foldlevel=4<CR>
" nmap <leader>f5 :set foldlevel=5<CR>
" nmap <leader>f6 :set foldlevel=6<CR>
" nmap <leader>f7 :set foldlevel=7<CR>
" nmap <leader>f8 :set foldlevel=8<CR>
" nmap <leader>f9 :set foldlevel=9<CR>


" -----------------------------------------------------------------
" "搜索函数定义的位置
"Grep serching
" -----------------------------------------------------------------
map <C-g> :Grep  <C-R>=expand("<cword>")<CR> **/*.php



"------------------------------------------------------
"设置 文件dos格式或者unix 格式
"------------------------------------------------------
"set ff=dos
set ff=unix

"------------------------------------------------------
"光标横线
"------------------------------------------------------
"highlight CursorLine guibg=lightblue ctermbg=lightgray

"------------------------------------------------------
"AutoComplPop settings
"------------------------------------------------------
let g:acp_behaviorSnipmateLength = 1
let g:acp_behaviorSnipmateLength=1
let g:acp_behaviorKeywodLength = 1
let g:acp_behaviorHtmlOmniLength = 1
let g:acp_behaviorCssOmniPopetyLength = 1

"auto-popup is enabled at statup.
let g:acp_enableAtStatup = 1

"-----------------------------------------------------------------	
"OmniCppComplete
"-----------------------------------------------------------------	
set nocp
let OmniCpp_NamespaceSeach = 1
let OmniCpp_GlobalScopeSeach = 1
let OmniCpp_ShowAccess = 1
let OmniCpp_ShowPototypeInAbb = 1 " show function paametes
let OmniCpp_MayCompleteDot = 1 " autocomplete afte .
let OmniCpp_MayCompleteAow = 1 " autocomplete afte ->
let OmniCpp_MayCompleteScope = 1 " autocomplete afte ::
let OmniCpp_DefaultNamespaces = ["std", "_GLIBCXX_STD"]
" automatically open and close the popup menu / peview window
au CursorMovedI,InsertLeave * if pumvisible() == 0|silent! pclose|endif 
set completeopt=menuone,menu,longest,preview
autocmd FileType python set omnifunc=pythoncomplete#Complete
autocmd FileType javascript set omnifunc=javascriptcomplete#CompleteJS
autocmd FileType html set omnifunc=htmlcomplete#CompleteTags
autocmd FileType css set omnifunc=csscomplete#CompleteCSS
autocmd FileType xml set omnifunc=xmlcomplete#CompleteTags
autocmd FileType php set omnifunc=phpcomplete#CompletePHP
autocmd FileType c set omnifunc=ccomplete#Complete
au BufNewFile,Bufread,BufEnter *.cpp,*.hpp set omnifunc=omni#cpp#complete#Main
autocmd FileType cpp set omnifunc=cppcomplete#CompleteCPP

" 设置字典 ~/.vim/dict/文件的路径
"autocmd filetype javascipt set dictionay=$VIMFILES/dict/javascipt.dict
"autocmd filetype css set dictionay=$VIMFILES/dict/css.dict
"autocmd filetype php set dictionay=$VIMFILES/dict/php.dict

 "打开javascript折叠
let b:javascript_fold=1

"映射转换语法。快捷键：html:,1  php:,2  javascipt,3  css,4 defalu ,5
nmap <leader>seth :set filetype=html<Cr>
nmap <leader>setc :set filetype=css<Cr>
nmap <leader>setj :set filetype=javascipt<Cr>
nmap <leader>setp :set filetype=php<Cr>
nmap <leader>setd :set filetype=default<Cr>
nmap <leader>setx :set filetype=xml<Cr>

"------------------------------------------------------
"zencoding settings
"------------------------------------------------------

"let g:user_zen_expandabbr_key = '<c-y>'
"let g:use_zen_complete_tag = 1

"-----------------------------------------------------------------
"编辑按键映射	
"-----------------------------------------------------------------
" Tab操作快捷方式!
nmap <C-TAB> :tabnext<Cr>
nmap <C-S-TAB> :tabpev<Cr>

"关于tab的快捷键
map tn :tabnext<cr>
map tp :tabpevious<cr>
map td :tabnew <cr>
map te :tabedit<cr>
map tc :tabclose<cr>

"窗口跳转
"按住ctrl键，按一次w键，再按一次表示方向的h或j或k或l 
"映射光标在窗口间移动的快捷键
nmap <C-h> <C-w>h
nmap <C-j> <C-w>j
nmap <C-k> <C-w>k
nmap <C-l> <C-w>l
nmap <leader>w <C-w>v<C-w>l

"------------------------------------------------------
"taglist settings
"------------------------------------------------------
"设置F9键 打开 taglist或者关闭
map <silent> <F9> :TlistToggle<Cr>
"生成一个tags文件
nmap <F10> <Esc>:!ctags - *<Cr> 
let Tlist_Auto_Highlight_Tag = 1
let Tlist_Auto_Open = 1
let Tlist_Auto_Update = 1 

"如果Taglist窗口是最后一个窗口则退出vim
let Tlist_Exit_OnlyWindow=1

"让当前不被编辑的文件的方法列表自动折叠起来
"let Tlist_File_Fold_Auto_Close=1

"只显示当前文件的tag
let Tlist_Show_One_File = 1

"设定linux系统中的ctags cmd 位置
let Tlist_Ctags_Cmd = '/bin/ctags.exe'
let Tlist_Show_Menu = 1

"ctags set
set tags=tags
set tags+=./tags
set tags+=~/tag/tags
set tags+=/cygdive/e/xampplite/htdocs/aitupu/tags

"set tags+=./tags
set autochdir

"在右侧显示taglist窗口
let Tlist_Use_Right_Window = 1   
let g:ctags_statusline=1

" Override how taglist does javascript
let g:tlist_javascript_settings ='javascript;f:function;c:class;m:method;p:property;v:global'
highlight MyTagListTagName guifg=blue ctermfg=blue

"单击打开 tag name
let Tlist_Use_SingleClick = 1

"Tlist_WinHeight 
let Tlist_WinHeight = 20


" -----------------------------------------------------------------
" Calendar settings	
" -----------------------------------------------------------------
let g:calendar_diary = "/cygdrive/e/diary"

" open the Calendar
map <F4> :Calendar<cr>

"-----------------------------------------------------------------
""插入模式的移动
"-----------------------------------------------------------------
imap <C-j> <Down>
imap <C-k> <Up>
imap <A-h> <Left>
imap <A-l> <right>
imap <c-o> <esc>o








"------------------------------------------------------
"snipMate settings
"------------------------------------------------------

let g:snips_author = 'jack chim <ziikii1@qq.com>'
"set ft=html.eruby
"autocmd FileType python set ft=python.django
"autocmd FileType html set ft=htmldjango.html
"" Shortcut for reloading snippets, useful when developing
nnoremap ,smr <esc>:exec ReloadAllSnippets()<cr>


"------------------------------------------------------
"phpfolding settings
"------------------------------------------------------
map <F5> <Esc>:EnableFastPHPFolds<Cr>
map <F6> <Esc>:EnablePHPFolds<Cr>
map <F7> <Esc>:DisablePHPFolds<Cr>
"默认不显示 phpfolding
"let g:DisableAutoPHPFolding = 1   

" -----------------------------------------------------------------
" bufExplorer插件的设置	
" -----------------------------------------------------------------
" let g:bufExplorerSortBy='mru'
" let g:bufExplorerSplitRight=0     
" let g:bufExplorerSplitVertical=1    .
" let g:bufExplorerSplitVertSize = 30 
" let g:bufExplorerUseCurrentWindow=1 
" autocmd BufWinEnter \[Buf\ List\] setl nonumber

" -----------------------------------------------------------------
" winManager插件的设置	
" -----------------------------------------------------------------
" let g:winManagerWidth = 30
" let winManagerWindowLayout = 'TagList , BufExplorer'
" let g:defaultExplorer = 1
" nmap <silent> <leader>wm :WMToggle<cr> 
" let loaded_winmanager = 1




"------------------------------------------------------
"lookupfile settings
"------------------------------------------------------
"map \lut :LUTags<CR>
"map \lup :LUPath<CR>
"map \lub :LUBufs<CR>
"map \luw :LUWalk<CR> 
"map \lua :LUArgs<CR> 

"------------------------------------------------------
"matchit settings
"------------------------------------------------------
"let loaded_matchit = 1


"------------------------------------------------------
"project settings
"------------------------------------------------------
"禁止载入 project 插件
"let loaded_project = 1


"------------------------------------------------------
"设置 文件dos格式或者unix 格式
"------------------------------------------------------
"set ff=dos
set ff=unix

"-----------------------------------------------------------------
"Project settings 	
"-----------------------------------------------------------------
"map \p :Project<CR> 
"------------------------------------------------------
"grep settings
"------------------------------------------------------
"nnoremap <silent> <F3> :Grep<CR>
"let Grep_Cygwin_Find = 1

"-----------------------------------------------------------------
"vcscommand.vim : CVS/SVN/SVK/git/hg/bzr integration plugin 	
"CVSROOT should be set to something like:
":ext:user@host:/path_to_repository

"-----------------------------------------------------------------

set statusline=%<%f\ %{VCSCommandGetStatusLine()}\ %h%m%r%=%l,%c%V\ %P
"SVN 
let VCSCommandSVNExec = 'svn'
let b:VCSCommandVCSType='svn'
" let VCSCommandSVNDiffExt = ''
" let VCSCommandSVNDiffOpt = ''
nmap ,sa :VCSAdd
nmap ,sn :VCSAnnotate
nmap ,sN :VCSAnnotate!
nmap ,sc :VCSCommit
nmap ,sD :VCSDelete
nmap ,sd :VCSDiff
nmap ,sg :VCSGotoOriginal
nmap ,sG :VCSGotoOriginal!
nmap ,si :VCSInfo
nmap ,sl :VCSLog
nmap ,sL :VCSLock
nmap ,sr :VCSReview
nmap ,ss :VCSStatus
nmap ,su :VCSUpdate
nmap ,sU :VCSUnlock
nmap ,sv :VCSVimDiff
"SVN HELP
nmap ,svna :! svn add --force *
nmap ,svnc :! svn commit -m "Firt commit!"
nmap ,svnb :! svn blame
nmap ,svncat :!svn cat
nmap ,svn1  :! svn cleanup
nmap ,svn2 :! svn copy
nmap ,svn3 :! svn delete <FILENAME> 
nmap ,svn4 :! svn diff <本地文件的路径> <项目存储库的URL>
nmap ,svn5 :! svn Info
nmap ,svn6 :! svn list 
nmap ,svn7 :! svn log 
nmap ,svn8 :! svn merge
nmap ,svn9 :! svn mkdir <PATH>
nmap ,svn10 :! svn move
nmap ,svn11 :! svn  resolved
nmap ,svn12 :! svn revert
nmap ,svn13 :! svn status
nmap ,svn14 :!svn update


"Git 
 let VCSCommandGitExec='git'
" let b:VCSCommandVCSType='git'
" 优先
" let VCSCommandVCSTypeOverride = 'svn'
" let VCSCommandVCSTypePreference = 'svn'
"let VCSCommandDisableMappings=1
nmap ,ga :VCSAdd
nmap ,gn :VCSAnnotate
nmap ,gN :VCSAnnotate!
nmap ,gc :VCSCommit
nmap ,gD :VCSDelete
nmap ,gd :VCSDiff
nmap ,gg :VCSGotoOriginal
nmap ,gG :VCSGotoOriginal!
nmap ,gi :VCSInfo
nmap ,gl :VCSLog
nmap ,gL :VCSLock
nmap ,gr :VCSReview
nmap ,gs :VCSStatus
nmap ,gu :VCSUpdate
nmap ,gU :VCSUnlock
nmap ,gv :VCSVimDiff

" My Git help
nmap ,git :! git config –List
nmap ,gith :! git help <verb>
nmap ,gitr :! git remote add origin git@github.com:ziikii/n900_bay.git
nmap ,gitp :! git push  origin master
nmap ,gitv :! git remote -v 
nmap ,gitb :! git branch [branch-name]
nmap ,gitc :! git checkout [branch-name]
nmap ,gitm :! git mergy [branch-name]
nmap ,gitssh :! ssh -T git@github.com













" -----------------------------------------------------------------
" AutoCloseTag	
"Make it so AutoCloseTag works for xml and xhtml files as well
" -----------------------------------------------------------------
au FileType xhtml,xml so ~/.vim/ftplugin/html_autoclosetag.vim
" au FileType xhtml,xml ru ftplugin/html/autoclosetag.vim
"空格键折开


" -----------------------------------------------------------------
" Fuzzy Find file, tree, buffer, line	
" -----------------------------------------------------------------
nmap <leader>ff :FufFile **/<CR>
nmap <leader>ft :FufFile<CR>
nmap <leader>fb :FufBuffer<CR>
nmap <leader>fl :FufLine<CR>
nmap <leader>fr	:FufRenewCache<CR>
						

" -----------------------------------------------------------------
" php-doc commands	
"inoremap <C-P> <ESC>:call PhpDocSingle()<CR>i
"nnoremap <C-P> :call PhpDocSingle()<CR>
"vnoremap <C-P> :call PhpDocRange()<CR> 
" -----------------------------------------------------------------
nmap <leader>pd :call PhpDocSingle()<CR>
vmap <leader>pd :call PhpDocRange()<CR>

" -----------------------------------------------------------------
" JSON	
" -----------------------------------------------------------------
nmap <leader>jt <Esc>:%!python -m json.tool<CR><Esc>:set filetype=json<CR>

" -----------------------------------------------------------------
" quickfix	
" :cc                显示详细错误信息 ( :help :cc )
" :cp                跳到上一个错误 ( :help :cp )
" :cn                跳到下一个错误 ( :help :cn )
" :cl                列出所有错误 ( :help :cl )
" :cw                如果有错误列表，则打开quickfix窗口 ( :help :cw )
" :col               到前一个旧的错误列表 ( :help :col )
" :cnew              到后一个较新的错误列表 ( :help :cnew ) 
" " Toggle the quickfix window {{{
" " From Steve Losh,
" http://learnvimscriptthehardway.stevelosh.com/chapters/38.html 
" -----------------------------------------------------------------
" set makeprg=gcc\ -Wall\ -ohello\ hello.c 
" autocmd FileType c,cpp  map <buffer> <leader><space> :w<cr>:make<cr>
" nmap <leader>cn :cn<cr>
" nmap <leader>cp :cp<cr>
" nmap <leader>cw :cw 10<cr> 
nnoremap <C-q> :call <SID>QuickfixToggle()<cr>

let g:quickfix_is_open = 0

function! s:QuickfixToggle()
    if g:quickfix_is_open
        cclose
        let g:quickfix_is_open = 0
        execute g:quickfix_return_to_window . "wincmd w"
    else
        let g:quickfix_return_to_window = winnr()
        copen
        let g:quickfix_is_open = 1
    endif
endfunction
" }}}

" -----------------------------------------------------------------
" Bexec : Execute script in buffer and display output in buffer	
" \bx   or   :Bexec
" \bx   or   :call BexecVisual()
"
" -----------------------------------------------------------------
" nmap <Leader>bx :call Bexec()<CR>
" vmap <Leader>bx :call BexecVisual()<CR>
let bexec_outputmode='append'

" -----------------------------------------------------------------
" Screen setting! 	
"  1. Edit a python file
" $ vim something.py
" 2. Decide you want to run all or pieces of the code in an interactive python
" shell
" :ScreenShell python
" 3. Send code from a vim buffer to the shell
" :ScreenSend
" 4. Quit the screen session and return to your original vim session
" :ScreenQuit
" or
" :qa
" -----------------------------------------------------------------
  function! s:ScreenShellListener()
    if g:ScreenShellActive
      nmap <C-c><C-c> :ScreenSend<cr>
      nmap <C-c><C-x> :ScreenQuit<cr>
    else
      nmap <C-c><C-c> :ScreenShell<cr>
    endif
  endfunction
pwd

  nmap <C-c><C-c> :ScreenShell<cr>
  augroup ScreenShellEnter
    autocmd User * call <SID>ScreenShellListener()
  augroup END
  augroup ScreenShellExit
    autocmd User * call <SID>ScreenShellListener()
  augroup END

" -----------------------------------------------------------------
" bufExplorer setting 	
" To start exploring in the current window, use: >
 " \be   or   :BufExplorer
" To start exploring in a newly split horizontal window, use: >
 " \bs   or   :BufExplorerHorizontalSplit
" To start exploring in a newly split vertical window, use: >
 " \bv   or   :BufExplorerVerticalSplit

" -----------------------------------------------------------------



" Quickly  get out of insert mode without your fingers having to leave the
" home row (either use 'jj' or 'jk')
inoremap jj <Esc>
inoremap kk <Esc>

" -----------------------------------------------------------------
" ConqueTerm setting 	
" -----------------------------------------------------------------
" :ConqueTerm bash
" :ConqueTerm mysql -h localhost -u joe_lunchbox Menu
" :ConqueTerm Powershell.exe


"=================================================================
"个人配置结束
"=================================================================
