// This file holds the main code for the plugin. It has access to the *document*.
// You can access browser APIs such as the network by creating a UI which contains
// a full browser environment (see documentation).

// Runs this code if the plugin is run in Figma
if (figma.editorType === 'figma') {
  // This plugin will open a window to prompt the user to enter a number, and
  // it will then create that many rectangles on the screen.

  // This shows the HTML page in "ui.html".
  figma.showUI(__html__);

  figma.ui.resize(400, 300)
  // Calls to "parent.postMessage" from within the HTML page will trigger this
  // callback. The callback will be passed the "pluginMessage" property of the
  // posted message.
  figma.ui.onmessage = msg => {
    // One way of distinguishing between different types of messages sent from
    // your HTML page is to use an object with a "type" property like this.
    if (msg.type === 'create-shapes') {

      // const nodes: SceneNode[] = [];
      // for (let i = 0; i < msg.count; i++) {
      //   const rect = figma.createRectangle();
      //   rect.x = i * 150;
      //   rect.fills = [{type: 'SOLID', color: {r: 1, g: 0.5, b: 0}}];
      //   figma.currentPage.appendChild(rect);
      //   nodes.push(rect);
      // }
      // figma.currentPage.selection = nodes;
      // figma.viewport.scrollAndZoomIntoView(nodes);

 

    figma.ui.postMessage(   figma.currentPage.selection)
      
      // for (const node of figma.currentPage.selection) {
      //   if ("opacity" in node) {
      //     node.opacity *= 0.5
      //   }
      // }

    }

    if (msg.type === 'cancel') {
      figma.closePlugin();
    }

    // Make sure to close the plugin when you're done. Otherwise the plugin will
    // keep running, which shows the cancel button at the bottom of the screen.
    //figma.closePlugin();
  };

// If the plugins isn't run in Figma, run this code
};
