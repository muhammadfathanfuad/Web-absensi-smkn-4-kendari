/*
Template Name: Taplox- Responsive Bootstrap 5 Admin Dashboard
Author: Stackbros
File: form - Dropzone js
*/

// Dropzone
import { Dropzone } from "dropzone";

Dropzone.autoDiscover = false

var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
dropzonePreviewNode.id = "";
if (dropzonePreviewNode) {
    var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
    dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
    var dropzone = new Dropzone(".dropzone", {
        url: 'https://httpbin.org/post',
        method: "post",
        previewTemplate: previewTemplate,
        previewsContainer: "#dropzone-preview",
    });
}
