<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>GLS Label Creator</title>
    <link rel="stylesheet" type="text/css" media="screen" href="/view/css/main.css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
</head>
<body>

<div id="wrapper">
    <header>
        <div id="header_inner clearfix">
            <div id="logo">
                <img src="/view/images/Logo_GLS.jpg" height="40px" alt="Logo GLS" />
            </div>
            <h1 id="title">GLS Label Creator</h1>
        </div>
    </header>

    <main class="clearfix">
        <div id="error_success">
            <?php

                global $errors;
                foreach($errors as $error) {
                    ?><p class="error"><?php echo $error->message; ?></p><?php
                }
            ?>
        </div>
        <div id="create_from_interface" class="mpt">
            <h1>Create from Interface</h1>
            <form method="post" action="/create_from_interface/">
                <h2>Parameters</h2>
                <table>
                    <thead>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Description</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Mode</td>
                            <td>
                                <select name="mode" id="mode">
                                    <option <?php echo (resempty($_POST,'mode')=="business") ? 'selected':''; ?> value="business">Business Versand</option>
                                    <option <?php echo (resempty($_POST,'mode')=="express") ? 'selected':''; ?> value="express">Express Versand</option>
                                </select>
                            </td>
                            <td>DIN paper size</td>
                        </tr>
                        <tr>
                            <td>Package Label ID *</td>
                            <td><input type="text" name="package_label" id="package_label" value="<?php echo resempty($_POST,'package_label'); ?>" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Package Number *</td>
                            <td><input type="text" name="package_number" id="package_number" value="<?php echo resempty($_POST,'package_number'); ?>" /></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Label: Begin X</td>
                            <td><input type="text" name="pdf_beginx" id="pdf_beginx" value="<?php echo resempty($_POST,'pdf_beginx'); ?>" /></td>
                            <td>A number.</td>
                        </tr>
                        <tr>
                            <td>Label: Begin Y</td>
                            <td><input type="text" name="pdf_beginy" id="pdf_beginy" value="<?php echo resempty($_POST,'pdf_beginy'); ?>" /></td>
                            <td>A number.</td>
                        </tr>
                        <tr>
                            <td>Paper Size</td>
                            <td>
                                <select name="pdf_papersize" id="pdf_papersize">
                                    <option <?php echo (resempty($_POST,'pdf_papersize')=="A4") ? 'selected':''; ?> value="A4">A4</option>
                                    <option <?php echo (resempty($_POST,'pdf_papersize')=="A5") ? 'selected':''; ?> value="A5">A5</option>
                                </select>
                            </td>
                            <td>DIN paper size</td>
                        </tr>
                        <tr>
                            <td>Filename Prefix</td>
                            <td><input type="text" name="pdf_filename_prefix" id="pdf_filename_prefix" value="<?php echo resempty($_POST,'pdf_beginy'); ?>" /></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>


                <h2>GLS Tag Input</h2>


                <p>Input Type *</p>
                <p>
                    <select name="tags_format" id="tags_format">
                        <option value="string" <?php echo (resempty($_POST,'tags_format')=="string") ? 'selected':''; ?>>GLS prepared Tag String</option>
                        <option value="comma" <?php echo (resempty($_POST,'tags_format')=="comma") ? 'selected':''; ?>>GLS Tags by Tag ID:Value</option>
                        <option value="json" <?php echo (resempty($_POST,'tags_format')=="json") ? 'selected':''; ?>>GLS Tags as ready JSON</option>
                    </select>
                </p>
                <p>
                    Tag Input *
                </p>
                <p>
                    <textarea name="gls_input" id="gls_input"><?php echo resempty($_POST,'gls_input'); ?></textarea>
                </p>
                <p>
                    <input type="checkbox" name="create_json_only" value="1" <?php echo (resempty($_POST,'create_json_only')=="1") ? 'checked':''; ?> /> Create JSON (PDF otherwise)
                </p>

               <p><input type="submit" value="Create" /></p>
            </form>
        </div>

        <div id="create_from_json" class="mpt">
            <?php
            $json = resempty($_POST,'data');

            if($json=="") {
                global $output_json;
                if($output_json!="") {
                    $json = json_encode($output_json,JSON_PRETTY_PRINT);
                }
            }
            ?>
            <h1>Create from JSON</h1>
            <form method="post" action="/create/" enctype="multipart/form-data">
                <textarea name="data" id="data"><?php echo $json; ?></textarea>
                <p><input type="submit" value="Create" /></p>
            </form>

        </div>

    </main>

    <footer>
        <div id="footer_inner">
            <a href="http://www.gnu.org/licenses/gpl-3.0.en.html">GPLv3 licensed</a>. Author: David Stöckl (<a href="http://www.supermarktonline.at">supermarktonline.at</a>)
        </div>
    </footer>
</div>
</body>
</html>