using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ConsoleApp1
{
	class Item
	{
			public int inx { get; set; }
			public string country { get; set; }
			public int countryCoded { get; set; }
			public string degree { get; set; }
			public int degreeCoded { get; set; }
			public string eng { get; set; }
			public int engCoded { get; set; }
			public int fieldGroup { get; set; }
			public bool fund { get; set; }
			public double gpaBachelors { get; set; }
			public double gpaMasters { get; set; }
			public bool gre { get; set; }
			public bool highLevelBachUni { get; set; }
			public bool highLevelMasterUni { get; set; }
			public int papersGLOB { get; set; }
			public int papersIRAN { get; set; }
			public string targetUni { get; set; }
			public int uniRank { get; set; }
			public int year { get; set; }
	}

	class Program
	{
		static void Main(string[] args)
		{
			var filePath = @"C:\Users\Master\Dropbox\DataMiningProject Team Folder\DM17\06_DM_UseAppliedUni\01_Preprocessing\cw.csv";
			var cw = File.ReadLines(filePath).Select(x => x.Split(','));
			filePath = @"C:\Users\Master\Dropbox\DataMiningProject Team Folder\DM17\06_DM_UseAppliedUni\01_Preprocessing\Flip.json";
			var ls= LoadJson(filePath);

			var count = 0;
			foreach (var item in ls)
			{
				if (item.targetUni == "n/a") continue;
				var x = cw.ToList().Find(obj => item.targetUni.Contains(obj[1]));
				if (x == null) continue;
				item.uniRank = Convert.ToInt32(x[0]);
				count++;
			}
			Console.WriteLine(count);
			SaveJson(ls);
		}



		public static List<Item> LoadJson(string path)
		{
			using (StreamReader r = new StreamReader(path))
			{
				string json = r.ReadToEnd();
				List<Item> items = JsonConvert.DeserializeObject<List<Item>>(json);
				return items;
			}
		}
		public static void SaveJson(List<Item> items)
		{
			using (StreamWriter file = File.CreateText(@"xx.json"))
			{
				JsonSerializer serializer = new JsonSerializer();
				serializer.Serialize(file, items);
			}
		}
	}
}
