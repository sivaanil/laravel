<h2>Localization Framework in Unified</h2>
<hr />
<ul>
    <li>Localization is handled by the server. All translation packs will be located on the server.</li>
    <li>Vocabulary for translation will be pushed to the front end by a console command (lang:update)</li>
    <li>Vocabulary files will be written to modules using the same mechanism, and will be written to the client when the module is installed</li>
</ul>
<br />
<p>
There are several benefits to this approach, including
removal of the need to change front end code from what is already
in place, or move language files, as well as the ability to have a central
repository of trnslation packs/files that can be affected more efficiently and quickly.
</p>


